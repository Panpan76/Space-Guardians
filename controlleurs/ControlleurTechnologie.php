<?php
/**
 * Controlleur Technologie
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurTechnologie extends Controlleur{

  ################
  ### Méthodes ###
  ################

  /**
   * index
   *
   * @return void
   */
  public function index(){
    $ge = GestionnaireEntite::getInstance();

    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getOne();
    $technologies = $joueur->technologies;


    $this->render('technologie/index.php', 'Technologies', array(
      'technologies' => $technologies,
      'joueur'       => $joueur
    ));
  }


  /**
   * rechercher
   *
   * @return void
   */
  public function rechercher($id){
    $ge = GestionnaireEntite::getInstance();

    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::ENFANTS+$ge::PARENTS)->getOne();

    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getOne();
    $technologies = $joueur->technologies;

    foreach($technologies as $technologie){
      if($technologie->id == $id){
        break;
      }
    }

    if($planete->majStock($technologie->couts)){
      // On met à jour la date d'amélioration
      $technologie->date_amelioration = new DateTime();
      $technologie->date_amelioration->add(new DateInterval('PT'.floor($technologie->tempsRecherche).'S'));
      $ge->persist($joueur);
    }
    // On redirige vers la page des technologies
    Routeur::redirect('technologie');

  }


}


?>
