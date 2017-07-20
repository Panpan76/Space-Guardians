<?php
/**
 * Controlleur Planete
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurPlanete extends Controlleur{

  ################
  ### Méthodes ###
  ################

  /**
   * Voir
   *
   * @return void
   */
  public function voir($id){
    $ge = GestionnaireEntite::getInstance();
    $planete = $ge->select('Planete', array('id' => $id), $ge::ENFANTS+$ge::PARENTS+$ge::FRERES)->getOne();

    $this->render('planete/voir.php', $planete, array(
      'planete' => $planete
    ));
  }



}


?>
