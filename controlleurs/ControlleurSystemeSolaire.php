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
  public static function voir($id){
    $ge = GestionnaireEntite::getInstance();
    
    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::PARENTS+$ge::ENFANTS)->getOne();
    $systeme = $ge->select('SystemeSolaire', array('id' => $id), $ge::ENFANTS+$ge::PARENTS)->getOne();

    $controlleur = new Controlleur();

    $controlleur->render('SystemeSolaire/voir.php', $systeme->nom, array(
      'planeteSelect' => $planete,
      'systeme'       => $systeme,
    ));
  }

}


?>
