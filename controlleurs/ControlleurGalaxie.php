<?php
/**
 * Controlleur Galaxie
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurGalaxie extends Controlleur{

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
    $galaxie = $ge->select('Galaxie', array('id' => $id))->getOne();

    $this->render('galaxie/voir.php', $galaxie->nom, array(
      'galaxie' => $galaxie
    ));
  }



  public function nouvelle(){
    $ge = GestionnaireEntite::getInstance();

    $galaxie = new Galaxie();
    $galaxie->nom = 'Super galaxie';
    $galaxie->largeur = 200;
    $galaxie->hauteur = 100;

    var_dump($galaxie);

    $ge->persist($galaxie);

    Routeur::redirect("galaxie/$galaxie->id");
  }



}


?>
