<?php
/**
 * Controlleur Joueur
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurJoueur extends Controlleur{

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
    $joueur = $ge->select('Joueur', array('id' => $id))[0];

    $this->render('joueur/voir.php', $joueur->pseudo, array(
      'joueur' => $joueur
    ));
  }

}


?>
