<?php
/**
 * Joueur
 *
 * @author Panpan76
 * @version 1.0
 */
class Joueur extends EntiteMere{

  #################
  ### Variables ###
  #################

  protected $id;
  protected $pseudo;
  protected $motDePasse;
  protected $dateInscription;
  protected $race;
  protected $amis;
  protected $planetes;


  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();
    $planete = $ge->select('Planete', array('proprietaire' => 'NULL'), GestionnaireEntite::PARENTS, true, 1)->aleatoire()->getOne();

    $planete->proprietaire = $this;

    $ge->persist($planete);
  }
}

?>
