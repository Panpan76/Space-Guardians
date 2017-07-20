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

    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::FRERES+$ge::PARENTS)->getOne();
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

    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::ENFANTS+$ge::FRERES+$ge::PARENTS)->getOne();

    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::FRERES+$ge::PARENTS)->getOne();
    $technologies = $joueur->technologies;

    foreach($technologies as $technologie){
      if($technologie->id == $id){
        break;
      }
    }

    $maj = true;

    // Si on a les ressources suffisante pour construire le batiment
    foreach($technologie->couts as $idRessource => $cout){
      if($planete->stocks[$idRessource]->quantite > $cout){
        // On recalcul les stocks en soustrayant le cout du batiment
        $stock = $planete->stocks[$idRessource];
        $stock->quantite -= $cout;
      }
      else{
        // Si une ressource est insuffisante, on annule
        $maj = false;
      }
    }

    if($maj){
      // On met à jour la date de création du batiment pour qu'il produise à partir de maintenant
      $technologie->date_recherche = new DateTime();
      // On met à jour la date d'amélioration
      $technologie->date_amelioration = new DateTime();
      $technologie->date_amelioration->add(new DateInterval('PT'.floor($technologie->tempsRecherche).'S'));
      foreach($planete->batiments as $batiment){
        $batiment->date_construction = new DateTime();
      }
      $ge->persist($planete);
      $ge->persist($joueur);
    }

    // On redirige vers la page des technologies
    Routeur::redirect('technologie');

  }


}


?>
