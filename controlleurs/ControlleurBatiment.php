<?php
/**
 * Controlleur Batiment
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurBatiment extends Controlleur{

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

    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::ENFANTS+$ge::FRERES+$ge::PARENTS)->getOne();
    $batiments = $planete->batiments;


    $this->render('batiment/index.php', 'Batiments', array(
      'batiments' => $batiments,
      'planete'   => $planete
    ));
  }


  /**
   * construire
   *
   * @return void
   */
  public function construire($id){
    $ge = GestionnaireEntite::getInstance();

    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::ENFANTS+$ge::FRERES+$ge::PARENTS)->getOne();
    $batiments = $planete->batiments;

    foreach($batiments as $batiment){
      if($batiment->id == $id){
        break;
      }
    }

    $maj = true;
    // Si on a les ressources suffisante pour construire le batiment
    foreach($batiment->couts as $idRessource => $cout){
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
      $batiment->date_construction = new DateTime();
      // On met à jour la date d'amélioration
      $batiment->date_amelioration = new DateTime();
      $batiment->date_amelioration->add(new DateInterval('PT'.floor($batiment->tempsConstruction).'S'));
      $ge->persist($planete);
    }

    // On redirige vers la page des batiments
    Routeur::redirect('batiment');
  }


}


?>
