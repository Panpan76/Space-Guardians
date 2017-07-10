<?php
/**
 * Galaxie
 *
 * @author Panpan76
 * @version 1.0
 */
class Galaxie extends EntiteMere{

  #################
  ### Variables ###
  #################

  protected $id;
  protected $nom;
  protected $largeur;
  protected $hauteur;
  protected $systemesSolaires;

  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();


    $systeme = new SystemeSolaire();
    $systeme->nom = 'test';
    $systeme->galaxie = $this;
    $systeme->centreX = 42;
    $systeme->centreY = 12;
    $systeme->rayon = 3;


    $ge->persist($systeme);
  }
}

?>
