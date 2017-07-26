<?php
/**
 * Controlleur Vaisseau
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurModele extends Controlleur{

  ################
  ### Méthodes ###
  ################

  public function index(){
    $ge = GestionnaireEntite::getInstance();

    $modeles  = $ge->select('ModeleVaisseau', array('joueur' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getAll();

    $this->render('modele/index.php', 'Modèles', array(
      'modeles' => $modeles
    ));
  }

  /**
   * Voir
   *
   * @return void
   */
  public static function voir($id){
    $ge = GestionnaireEntite::getInstance();

    $this->render('modele/voir.php', 'Modèle', array(
      // 'vaisseau' => $vaisseau
    ));
  }



  public function nouveau(){
    $ge = GestionnaireEntite::getInstance();

    $modele     = new ModeleVaisseau();
    $ressources = $ge->select('Ressource', array(), $ge::PARENTS)->getAll();
    $message    = '';

    if($data = $this->aDonnees()){
      $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::PARENTS)->getOne();

      $modele->nom    = $data['nom'];
      $modele->joueur = $joueur;
      // TODO Calculer la classe

      if($ge->persist($modele)){
        $modele = $ge->select('ModeleVaisseau', array(
          'joueur'  => $joueur->id,
          'nom'     => $modele->nom
        ), $ge::PARENTS)->getOne();

        $ress           = array();
        foreach($ressources as $ressource){
          $res = clone $ressource;
          $res->quantite = $data['ressource_'.$ressource->id];
          $ress[] = $res;
        }
        $modele->ressources = $ress;

        if($ge->persist($modele)){
          // Routeur::redirect("modele/$modele->id");
        }
      }
      else{
        $message = "Une erreur est survenue";
      }
    }

    $this->render('modele/nouveau.php', 'Créer un modèle', array(
      'message'     => $message,
      'modele'      => $modele,
      'ressources'  => $ressources
    ));
  }

  public function construire($modele, $nb){
    $ge = GestionnaireEntite::getInstance();

    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::ENFANTS+$ge::PARENTS)->getOne();
    $modele  = $ge->select('ModeleVaisseau', array('id' => $modele), $ge::ENFANTS+$ge::PARENTS)->getOne();

    $couts = array();
    foreach($modele->couts as $idRessource => $cout){
      $couts[$idRessource] = $cout*$nb;
    }

    if($planete->majStock($couts)){
      // On met à jour la date d'amélioration
      $vaisseaux = new VaisseauConstruction();
      $vaisseaux->planete   = $planete;
      $vaisseaux->modele    = $modele;
      $vaisseaux->quantite  = $nb;
      $vaisseaux->date      = new DateTime();
      $vaisseaux->date->add(new DateInterval('PT'.floor($nb*$modele->temps).'S'));
      $ge->persist($vaisseaux);
      var_dump('Ressources ok');
    }


    $modeles  = $ge->select('ModeleVaisseau', array('joueur' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getAll();

    $this->render('modele/index.php', 'Modèles', array(
      'modeles' => $modeles
    ));
    // Routeur::redirect('modele');
  }


}


?>
