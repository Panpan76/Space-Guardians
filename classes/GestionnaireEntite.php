<?php/** * Classe GestionnaireEntite * * Permet de gérer les entités * * @author  Panpan76 * @version 1.0 */class GestionnaireEntite{  const AUCUN       = 0b0000;  const ENFANTS     = 0b0001;  const PARENTS     = 0b0010;  const FRERES      = 0b0100; // Les enfants des parents  const BEAUXFRERES = 0b1000; // Les parents des enfants  #################  ### Variables ###  #################  /**   * @var PDO|null $pdo   Instance de PDO   */  private $pdo;  /**   * @var GestionnaireEntite|null $instance   Instance de la classe GestionnaireEntite   */  private static $instance = null;

  /**
   * @var array|null $entites   Liste de toutes les entités ayant déjà été récupérées
   */
  public $entites = array();  /**   * @var array|null $requetes   Liste de toutes les requêtes ayant déjà été effectuées   */  private $requetes = array();  /**   * @var array|null  $correspondances     Correspondances entre Entite et table   */  private $correspondances;  ################  ### Méthodes ###  ################  /**   * Constructeur de la classe GestionnaireEntite   *   * Visibilité privée pour utilisation du patron de conception Singleton   *   * @return GestionnaireEntite   */  private function __construct(){    try{      $this->pdo = new PDO(BDD_SGBD.':host='.BDD_HOST.';dbname='.BDD_BASE.';charset=utf8', BDD_USER, BDD_PASS);    }    catch(Exception $e){      echo $e;      return null;    }    $this->setCorrespondances(FICHIER_ENTITES);  }  /**   * Permet de récupérer l'instance de GestionnaireEntite   *   * Cette méthode met en place le patron de conception Singleton   *   * @return GestionnaireEntite   */  public static function getInstance(){    if(is_null(self::$instance)){      self::$instance = new self();    }    return self::$instance;  }

  /**
   * Permet de récupérer la liste des requetes executées
   *
   * @return array
   */
  public function getRequetes(){    return $this->requetes;  }  /**   * Permet de récupérer le nombre de requetes executées   *   * @return int   */  public function getNbRequetes(){    return count($this->getRequetes());  }


  public function select($entite, $where = array(), $mapping = self::PARENTS, $sauvegarde = true){
    if(($correspondances = $this->getCorrespondances($entite)) != false){
      $requete = $this->creerRequete($correspondances, $where);

      $sql = $this->pdo->prepare($requete);

      $this->requetes[] = array(
        'requete' => $requete,
        'succes'  => $sql->execute()
      );

      $resultats = array();
      while($res = $sql->fetch(PDO::FETCH_ASSOC)){
        $colonneID  = $correspondances['variables']['id']['colonne'];
        $id         = $res[$colonneID];
        if(!is_object($obj = $this->existe($entite, $id))){
          // Si l'entité n'existe pas, on la crée
          $obj = new $entite();
          // On charge les attributs "basiques"
          $this->charger($obj, $res, $mapping);

          // Si on veut la sauvegarder
          if($sauvegarde){
            // On la sauvegarde dans notre liste d'entités
            $this->entites[$entite][$id] = array(
              'obj'     => $obj, // L'entité
              'mapping' => $mapping // Le mapping correspondant
            );
          }
          // On charge les entités relationnelles selon le mapping
          $this->charger($obj, $res, $mapping, false);
          // Si une méthode postSelect existe sur l'objet, on l'appelle
          if(method_exists($obj, 'postSelect')){
            $obj->postSelect();
          }
        }

        // Si on l'a sauvegardé
        if($sauvegarde){
          // Si le mapping enegistré ne contient pas celui demandé
          if(($this->entites[$entite][$id]['mapping'] & $mapping) != $mapping){
            // On charge à nouveau les entités relationnelles selon le mapping manquant
            $mapping = $mapping ^ $this->entites[$entite][$id]['mapping'];
            $this->charger($obj, $res, $mapping, false);
            // Si une méthode postSelect existe sur l'objet, on l'appelle
            if(method_exists($obj, 'postSelect')){
              $obj->postSelect();
            }
          }
        }


        $resultats[] = $obj;
      }
      return new GestionnaireEntiteResultats($resultats);
    }
  }

  /**
   * Permet de savoir si une entité a déjà été chargé ou non
   *
   * @param string  $entite   Entité concernée
   * @param int     $id       Identifiant de l'entité
   *
   * @return object|false
   */
  private function existe($entite, $id){
    if(array_key_exists($entite, $this->entites)){
      if(array_key_exists($id, $this->entites[$entite])){
        return $this->entites[$entite][$id]['obj'];
      }
    }
    return false;
  }

  /**
   * Permet de charger une entité selon ses correspondaces avec les données
   *
   * @param object $entite  Entite devant être chargée
   * @param array $donnees  Données devant être chargées
   *
   * @return void
   */
  private function charger($entite, $donnees, $mapping, $simple = true){
    if(($correspondances = $this->getCorrespondances($entite)) != false){
      foreach($correspondances['variables'] as $attribut => $baseInfos){
        if(array_key_exists('colonne', $baseInfos) && $simple){
          $entite->$attribut = $donnees[$baseInfos['colonne']];
        }

        if(array_key_exists('relation', $baseInfos) && !$simple){
          $nextMapping = $mapping;
          switch($baseInfos['relation']){
            // On cherche les parents
            case 'n-1':
              // Si on veut les parents
                // echo $baseInfos['entite'].' '.$nextMapping.'<br />';
              if(($nextMapping & self::PARENTS) == self::PARENTS){
                // echo $baseInfos['entite'];
                // On retire les enfants
                $nextMapping = $nextMapping & ~self::ENFANTS;

                // Sauf si on veut explicitement les frères
                if(($nextMapping & self::FRERES) == self::FRERES){
                  $nextMapping = $nextMapping | self::ENFANTS;
                }
                if(is_object($entite->$attribut)){
                  $entite->$attribut = $entite->$attribut->id;
                }
                $entite->$attribut = $this->select($baseInfos['entite'], array(
                  'id' => $entite->$attribut
                ), $nextMapping)->getOne();
              }
            break;

            // On cherche les enfants
            case '1-n':
              // Si on veut les enfants
              if(($nextMapping & self::ENFANTS) == self::ENFANTS){
                // On retire les parents
                $nextMapping = $nextMapping & ~self::PARENTS;

                // Sauf si on veut explicitement les beaux-frères
                if(($mapping & self::BEAUXFRERES) == self::BEAUXFRERES){
                  $nextMapping = $nextMapping | self::PARENTS;
                }
                $entite->$attribut = $this->select($baseInfos['entite'], array(
                  $baseInfos['lien'] => $entite->id
                ), $nextMapping)->getAll();
              }
            break;

            // On cherche les enfants
            case 'n-n':
              // Si on veut les enfants
              // if(($nextMapping & self::ENFANTS) == self::ENFANTS){
                // TODO ManyToMany
                $entite->$attribut = $this->ManyToMany($baseInfos['entite'], $baseInfos['byTable'], array(
                  'colonne' => $baseInfos['from'],
                  'valeur' => $entite->id
                ), $baseInfos['to'], $nextMapping)->getAll();
              // }
            break;
          }
        }
      }
    }
  }

  private function ManyToMany($entite, $table, $source, $cible, $mapping){
    $colonne  = $source['colonne'];
    $valeur   = $source['valeur'];
    // La requête
    $requete = "SELECT * FROM $table WHERE $colonne = $valeur";
    $sql = $this->pdo->prepare($requete);

    $succes = $sql->execute();

    // On mémorise la requête et si elle a réussi ou échoué
    $this->requetes[] = array(
      'succes' => $succes,
      'requete' => $requete
    );

    $resultats = array();
    while($res = $sql->fetch(PDO::FETCH_ASSOC)){
      $obj = $this->select($entite, array('id' => $res[$cible]), $mapping, false)->getOne();
      foreach($res as $col => $val){
        if($col != $cible && $col != $colonne){
          $obj->$col = $val;
        }
      }
      // Si une méthode postSelect existe sur l'objet, on l'appelle
      if(method_exists($obj, 'postSelect')){
        $obj->postSelect();
      }
      // $this->entites[$entite][$res[$cible]] = array(
      //   'obj'     => $obj, // L'entité
      //   'mapping' => $mapping // Le mapping correspondant
      // );
      $resultats[] = $obj;
    }
    return new GestionnaireEntiteResultats($resultats);
  }


  /**
   * Permet de créer une requête selon des conditions
   *
   * @param array $correspondances  Les correspondances de l'entité devant être sélectionnée
   * @param array $where            Les conditions de la recherche
   *
   * @return string                 La requête
   */
  private function creerRequete($correspondances, $where = array()){
    $table = $correspondances['table'];

    $cherche = array();
    foreach($where as $colonne => $valeur){
      if(is_null($valeur)){
        $cherche[] = $correspondances['variables'][$colonne]['colonne']." IS NULL";
      }
      else{
        $cherche[] = $correspondances['variables'][$colonne]['colonne']." = '".$valeur."'";
      }
    }
    $where = 'WHERE '.implode(' AND ', $cherche);
    if(empty($cherche)){
      $where = '';
    }
    $requete = "SELECT * FROM $table $where";
    return $requete;
  }

  /**
   * Permet de faire persister (ajout ou modification) d'une entité dans la base de données
   *
   * @param Objet  $entite   Entité
   *
   * @return boolean
   */
  public function persist($entite){
    if(($correspondances = $this->getCorrespondances($entite)) != false){
      $infos = $correspondances;
      $table = $infos['table'];

      $champs   = array();
      $valeurs  = array();
      foreach($infos['variables'] as $variable => $base){
        $var = $entite->$variable;
        if(isset($base['colonne'])){
          $champs[] = $base['colonne'];
          switch($base['type']){
            case 'PK':
              if(isset($var)){
                $valeurs[] = $var;
              }
              else{
                array_pop($champs);
              }
              break;

            case 'string':
              $valeurs[] = "'$var'";
              break;

            case 'datetime':
              if(is_object($var) && get_class($var) == 'DateTime'){
                $date = $var->format('Y-m-d H:i:s');
                $valeurs[] = "'$date'";
              }
              else{
                $valeurs[] = "'$var'";
              }
              break;

            case 'objet':
              if(empty($var)){
                $valeurs[] = 'null';
              }
              else{
                if(is_object($entite->$variable)){
                  $valeurs[] = $var->id;
                }
                else{
                  $valeurs[] = $var; // L'id
                }
              }
              break;

            default:
              $valeurs[] = $var;
              break;
          }
        }
        if(isset($base['byTable']) && !is_null($var)){ // Relation ManyToMany
          $tableMany    = $base['byTable'];
          $champsMany   = array();
          $valeursMany  = array();

          $requete = "DESCRIBE $tableMany";
          $sql = $this->pdo->prepare($requete);

          $succes = $sql->execute();
          $this->requetes[] = array(
            'succes' => $succes,
            'requete' => $requete
          );

          $colonnesBase = $sql->fetchAll(PDO::FETCH_ASSOC);

          foreach($var as $objChild){
            foreach($colonnesBase as $colonneBase){
              $champsMany[] = $colonneBase['Field'];

              if($colonneBase['Field'] == $base['to']){
                $val = $objChild->id;
              }
              elseif($colonneBase['Field'] == $base['from']){
                $val = $entite->id;
              }
              else{
                $val = $objChild->{$colonneBase['Field']};
              }
              if(is_object($val)){
                if(get_class($val) == 'DateTime'){
                  $val = $val->format('Y-m-d H:i:s');
                }
              }
              if(is_null($val)){
                $val = 'null';
              }
              else{
                $val = "'$val'";
              }

              $valeursMany[] = $val;
            }
          }

          $updateMany = array();
          for($i = 0; $i < count($champsMany); $i++){
            $updateMany[] = $champsMany[$i].' = '.$valeursMany[$i];
          }

          $champsMany   = '('.implode(', ', $champsMany).')';
          $valeursMany  = '('.implode(', ', $valeursMany).')';
          $updateMany   = implode(', ', $updateMany);
          $requete = "INSERT INTO $tableMany $champsMany VALUES $valeursMany ON DUPLICATE KEY UPDATE $updateMany";
          $sql = $this->pdo->prepare($requete);

          $succes = $sql->execute();
          $this->requetes[] = array(
            'succes' => $succes,
            'requete' => $requete
          );

        }
      }

      $update = array();
      for($i = 0; $i < count($champs); $i++){
        $update[] = $champs[$i].' = '.$valeurs[$i];
      }


      $champs   = '('.implode(', ', $champs).')';
      $valeurs  = '('.implode(', ', $valeurs).')';
      $update   = implode(', ', $update);

      // La requête
      $requete = "INSERT INTO $table $champs VALUES $valeurs ON DUPLICATE KEY UPDATE $update";
      $sql = $this->pdo->prepare($requete);

      $succes = $sql->execute();

      // On mémorise la requête et si elle a réussi ou échoué
      $this->requetes[] = array(
        'succes' => $succes,
        'requete' => $requete
      );

      // Si on a déjà un ID, c'est que c'était un update, sinon un insert
      if(property_exists($entite, 'id') && $entite->id != null){
        $methode = 'postUpdate';
      }
      else{
        $entite->id = $this->pdo->lastInsertId();
        $methode = 'postInsert';
      }

      if(method_exists($entite, $methode)){
        $entite->$methode();
      }

      return $succes;
    }
    return false;
  }


  public function supprime($entite){
    if(($correspondances = $this->getCorrespondances($entite)) != false){
      $infos    = $correspondances;
      $table    = $infos['table'];
      $primaire = $infos['variables']['id']['colonne'];

      $requete = "DELETE FROM $table WHERE $primaire = $entite->id";
      $sql = $this->pdo->prepare($requete);

      $succes = $sql->execute();

      // On mémorise la requête et si elle a réussi ou échoué
      $this->requetes[] = array(
        'succes' => $succes,
        'requete' => $requete
      );
      return $succes;
    }
    return false;
  }

  /**
   * Permet de récupérer les correspondances définies selon l'entité voulu
   *
   * @param string|object $entite Entité voulue
   *
   * @return array|false
   */
  private function getCorrespondances($entite){
    if(is_object($entite)){
      $entite = get_class($entite);
    }
    if(array_key_exists($entite, $this->correspondances)){
      return $this->correspondances[$entite];
    }
    return false;
  }




  /**   * Permet de récupérer les correspondances depuis un fichier   *   * Charge les correspondances possibles pour le GestionnaireEntite à partir d'un fichier   *   * @param string  $fichier  Emplacement du fichier contenant les correspondances   *   * @return void   */  public function setCorrespondances($fichier){    // On vérifie que le fichier existe, sinon on stop    if(!file_exists($fichier)){      return false;    }    // On initialise les correspondances à null    $correspondances = array();    try{      // On récupère le type de fichier      $elements = explode('.', $fichier);      $type     = end($elements);      // On parse le fichier en entrée selon son type      switch($type){        case 'yml':          // TODO YAML file          break;        case 'php':          include $fichier;          break;      }    }    catch(Exception $e){      $f = __FILE__;      $l = __LINE__;      $m = __METHOD__;      $c = __CLASS__;      print("Une erreur est survenue dans $c::$m() ($f:$l) : $e\n");    }    $this->correspondances = $correspondances;  }}

?>