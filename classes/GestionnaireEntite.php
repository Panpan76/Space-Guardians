<?php/** * Classe GestionnaireEntite * * Permet de gérer les entités * * @author  Panpan76 * @version 1.0 */class GestionnaireEntite{  const AUCUN   = 0b0000;  const ENFANTS = 0b0001;  const FRERES  = 0b0010;  const PARENTS = 0b0100;  const COUSINS = 0b1000;  #################  ### Variables ###  #################  /**   * @var PDO|null $pdo   Instance de PDO   */  private $pdo;  /**   * @var GestionnaireEntite|null $instance   Instance de la classe GestionnaireEntite   */  private static $instance = null;

  /**
   * @var array|null $entites   Liste de toutes les entités ayant déjà été récupérées
   */
  private $entites;  /**   * @var array|null $requetes   Liste de toutes les requêtes ayant déjà été effectuées   */  private $requetes = array();  /**   * @var array|null  $correspondances     Correspondances entre Entite et table   */  private $correspondances;  ################  ### Méthodes ###  ################  /**   * Constructeur de la classe GestionnaireEntite   *   * Visibilité privée pour utilisation du patron de conception Singleton   *   * @return GestionnaireEntite   */  private function __construct(){    try{      $this->pdo = new PDO(BDD_SGBD.':host='.BDD_HOST.';dbname='.BDD_BASE.';charset=utf8', BDD_USER, BDD_PASS);    }    catch(Exception $e){      die($e);    }    $this->setCorrespondances(FICHIER_ENTITES);  }  /**   * Permet de récupérer l'instance de GestionnaireEntite   *   * Cette méthode met en place le patron de conception Singleton   *   * @return GestionnaireEntite   */  public static function getInstance(){    if(is_null(self::$instance)){      try{        self::$instance = new self();      }      catch(Exception $e){        die($e);      }    }    return self::$instance;  }

  /**
   * Permet de récupérer la liste des requetes executées
   *
   * @return array
   */
  public function getRequetes(){    return $this->requetes;  }  /**   * Permet de récupérer le nombre de requetes executées   *   * @return int   */  public function getNbRequetes(){    return count($this->getRequetes());  }

  /**
   * Permet de récupérer une ou plusieurs entités dans la base de données
   *
   * @param string  $classe   Nom de l'entité voulu
   * @param array   $where    Tableau de correspondance pour la recherche (spécifier les attributs de classe et nom les colonnes de la base)
   *
   * @return array
   */
  public function select($entite, $where = array(), $autre = self::PARENTS, $alea = false, $limit = 0){    if(!in_array($entite, array_keys($this->correspondances))){      $classe = get_class($entite);      die("La classe $classe n'a définie aucune correspondances");    }    $infos = $this->correspondances[$entite];    $table = $infos['table'];    if(!empty($where)){      // On cherche un cas existant      $res = $this->dejaInstancie($entite, $where);      if(is_object($res)){        return new GestionnaireEntiteResultats(array($res));      }      $recherche = array();      // On construit les paramètre de recherche      foreach($where as $attribut => $valeur){
        $var = $infos['variables'][$attribut]['colonne'];        $operateur = '=';
        if(is_array($valeur)){          $operateur = 'IN';          $valeur = '('.implode(', ', $valeur).')';        }        elseif(strtoupper($valeur) == 'NULL'){          $operateur = 'IS';        }        else{          $valeur = "'$valeur'";        }        $recherche[] = "$var $operateur $valeur";      }      $where = "WHERE ".implode(' AND ', $recherche);    }    else{      $where = '';    }
    // La requête
    $requete = "SELECT * FROM $table $where";

    if($alea){
      $requete .= " ORDER BY RAND()";
    }
    if($limit){
      $requete .= "LIMIT $limit";
    }

    $sql = $this->pdo->prepare($requete);    // On mémorise la requête et si elle a réussi ou échoué    $this->requetes[] = array(      'succes' => $sql->execute(),      'requete' => $requete    );    $resultats = array();    while($res = $sql->fetch(PDO::FETCH_ASSOC)){      $obj = new $entite();      // On stock le résultat dans $this->entites pour éviter de le créer à nouveau      $resultats[] = $this->entites[$entite][] = $obj;      // On charge dynamiquement l'objet      $this->charger($obj, $res, $autre);      // Si une méthode postSelect existe sur l'objet, on l'appelle      if(method_exists($obj, 'postSelect')){        $obj->postSelect();      }    }    return new GestionnaireEntiteResultats($resultats);  }
  /**
   * Permet de faire persister (ajout ou modification) d'une entité dans la base de données
   *
   * @param Objet  $obj   Entité
   *
   * @return boolean
   */
  public function persist($obj){    $classe = get_class($obj);    if(in_array($classe, array_keys($this->correspondances))){      $infos = $this->correspondances[$classe];      $table = $infos['table'];
      $champs   = array();
      $valeurs  = array();
      foreach($infos['variables'] as $variable => $base){
        $var = $obj->$variable;
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
              $valeurs[] = "'$var'";
              break;

            case 'objet':
              if(is_object($obj->$variable)){
                $valeurs[] = $var->id;
              }
              else{
                $valeurs[] = $var; // L'id
              }
              break;

            default:
              $valeurs[] = $var;
              break;
          }
        }
      }

      $update = array();
      for($i = 0; $i < count($champs); $i++){
        $update[] = $champs[$i].' = '.$valeurs[$i];
      }


      $champs   = '('.implode(', ', $champs).')';
      $valeurs  = '('.implode(', ', $valeurs).')';
      $update  = implode(', ', $update);

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
      if(isset($obj->id) && !empty($obj->id)){
        $methode = 'postUpdate';
      }
      else{
        $methode = 'postInsert';
      }

      $obj->id = $this->pdo->lastInsertId();

      if(method_exists($obj, $methode)){
        $obj->$methode();
      }

      return $succes;
    }
    else{
      die("La classe $classe n'a définie aucune correspondances");
    }
  }  /**   * Permet de faire la correspondance entre les colonnes de la table en base, et les attributs de l'entité   *   * @param string  $classe           Entité   * @param array   $donnees          Données   *   * @return Object   */  private function charger($entite, $donnees, $autre){    // Pour chaque correspondances    foreach($this->correspondances[get_class($entite)]['variables'] as $attribut => $infos){      // Si on a une entrée pour le colonne correspondant à cet attribut      if($infos['type']){        // On effectue un traitement selon le type de l'attribut        switch($infos['type']){          case 'objet':            // On charge dynamiquement le nouvel objet            switch($infos['relation']){              case '1-n':                if(($autre & self::ENFANTS) == self::ENFANTS){                  $entite->$attribut = $this->select($infos['entite'], array($infos['lien'] => $entite->id), $autre)->getAll();                }                break;              case 'n-1':                if(($autre & self::PARENTS) == self::PARENTS){                  $select = $autre;                  if(($autre & self::COUSINS) != self::COUSINS){ // Si on ne veut pas de cousins, on retire les enfants                    $select = $select & ~self::ENFANTS;                  }                  $entite->$attribut = $this->select($infos['entite'], array('id' => $donnees[$infos['colonne']]), $select)->getOne();                }                break;              case 'n-n':                if(($autre & self::FRERES) == self::FRERES){                  $select = $autre;                  if(($autre & self::COUSINS) != self::COUSINS){ // Si on ne veut pas de cousins, on retire les enfants                    $select = $select & ~self::ENFANTS;                  }                  $entite->$attribut = $this->ManyToMany($infos['entite'], $infos['byTable'], array(                    'colonne' => $infos['from'],                    'valeur' => $entite->id                  ), $infos['to'], $select);                }                break;            }            break;
          case 'array':            // TODO
            break;
          default:            // Par défaut, on attribut simplement la valeur à l'attribut            $entite->$attribut = $donnees[$infos['colonne']];            break;        }      }    }  }  /**   *   *   *   *   */  private function ManyToMany($entite, $table, $source, $cible, $autre){    $colonne  = $source['colonne'];    $valeur   = $source['valeur'];    // La requête    $requete = "SELECT $cible FROM $table WHERE $colonne = $valeur";    $sql = $this->pdo->prepare($requete);    $succes = $sql->execute();    // On mémorise la requête et si elle a réussi ou échoué    $this->requetes[] = array(      'succes' => $succes,      'requete' => $requete    );    $resultats = array();    while($res = $sql->fetch(PDO::FETCH_ASSOC)){      $resultats[] = $this->select($entite, array('id' => $res[$cible]), $autre)->getOne();    }    return $resultats;  }  public function count($entite, $where = array()){    if(!in_array($entite, array_keys($this->correspondances))){      $classe = get_class($entite);      die("La classe $classe n'a définie aucune correspondances");    }    $infos = $this->correspondances[$entite];    $table = $infos['table'];    if(!empty($where)){      // On cherche un cas existant      $res = $this->dejaInstancie($entite, $where);      if(is_object($res)){        return new GestionnaireEntiteResultats(array($res));      }      $recherche = array();      // On construit les paramètre de recherche      foreach($where as $attribut => $valeur){        $var = $infos['variables'][$attribut]['colonne'];        $operateur = '=';        if(is_array($valeur)){          $operateur = 'IN';          $valeur = '('.implode(', ', $valeur).')';        }        elseif(strtoupper($valeur) == 'NULL'){          $operateur = 'IS';        }        else{          $valeur = "'$valeur'";        }        $recherche[] = "$var $operateur $valeur";      }      $where = "WHERE ".implode(' AND ', $recherche);    }    else{      $where = '';    }    // La requête    $requete = "SELECT count(*) as nb FROM $table $where";    $sql = $this->pdo->prepare($requete);    // On mémorise la requête et si elle a réussi ou échoué    $this->requetes[] = array(      'succes' => $sql->execute(),      'requete' => $requete    );    $res = $sql->fetch(PDO::FETCH_ASSOC);    return $res['nb'];  }  /**   * Cherche si une entité a déjà était instancié   *   * @param string  $classe Nom de la classe   * @param array   $where  Paramètres de la recherche   *   * @return Objet|false   */  private function dejaInstancie($classe, $where){    // Si on a aucune entrée pour cette classe, on stop    if(!isset($this->entites[$classe])){      return false;    }    $trouve = false;    // On parcourt les objets sauvegardés    foreach($this->entites[$classe] as $obj){      // On parcourt les propriétés recherchées      foreach($where as $attribut => $valeur){
        if(substr($attribut, -2) == '!='){
          $attribut = substr($attribut, 0, -2);          if($obj->$attribut == $valeur){            $trouve = false;            break;          }        }
        else{          if($obj->$attribut != $valeur){            $trouve = false;            break;          }
        }        $trouve = true;      }      if($trouve){        return $obj;      }    }    return false;  }  /**   * Permet de récupérer les correspondances depuis un fichier   *   * Charge les correspondances possibles pour le GestionnaireEntite à partir d'un fichier   *   * @param string  $fichier  Emplacement du fichier contenant les correspondances   *   * @return void   */  public function setCorrespondances($fichier){    // On vérifie que le fichier existe, sinon on stop    if(!file_exists($fichier)){      return false;    }    // On initialise les correspondances à null    $correspondances = array();    try{      // On récupère le type de fichier      $elements = explode('.', $fichier);      $type     = end($elements);      // On parse le fichier en entrée selon son type      switch($type){        case 'yml':          // TODO YAML file          break;        case 'php':          include $fichier;          break;      }    }    catch(Exception $e){      $f = __FILE__;      $l = __LINE__;      $m = __METHOD__;      $c = __CLASS__;      print("Une erreur est survenue dans $c::$m() ($f:$l) : $e\n");    }    $this->correspondances = $correspondances;  }}

?>