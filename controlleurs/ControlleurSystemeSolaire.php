<?php
/**
 * Controlleur SystemeSolaire
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurSystemeSolaire extends Controlleur{

  ################
  ### Méthodes ###
  ################

  /**
   * Voir
   *
   * @return void
   */
  public function getJSON($id){
    $ge = GestionnaireEntite::getInstance();
    $systeme = $ge->select('SystemeSolaire', array('id' => $id))->getOne();

    echo $systeme->getJSON();
  }

  /**
   * Voir
   *
   * @return void
   */
  public function voir($id){
    $ge = GestionnaireEntite::getInstance();
    $systeme = $ge->select('SystemeSolaire', array('id' => $id))->getOne();

    var_dump($systeme);
    //
    // echo json_encode($systeme);
  }

}


?>
