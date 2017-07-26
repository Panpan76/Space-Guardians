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
  protected $technologies;
  protected $alliance;
  protected $rang;


  public function postSelect(){


    if(($technologie = $this->getRechercheEnCours()) != null){
      if($technologie->tempsRestant() <= 0){
        $technologie->niveau++;
        $technologie->date_recherche = $technologie->date_amelioration;
        $technologie->date_amelioration = null;
        $ge = GestionnaireEntite::getInstance();
        $ge->persist($this);
        $technologie->postSelect();
      }
    }
  }

  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();
    $planete = $ge->select('Planete', array('proprietaire' => null), $ge::AUCUN)->aleatoire()->getOne();

    // On ajoute les ressources à la planète
    $ressources = $ge->select('Ressource')->getAll();
    $ress = array();
    foreach($ressources as $ressource){
      $res = clone $ressource;
      $res->quantite = floor(1000*$res->coefficient); // Quantité initiale
      $ress[] = $res;
    }
    $planete->stocks = $ress;

    // On ajoute les batiments à la planète
    $batiments  = $ge->select('Batiment', array(), $ge::AUCUN)->getAll();
    $bats = array();
    foreach($batiments as $batiment){
      $bat = clone $batiment;
      $bat->niveau = 0;
      $bat->date_construction = new DateTime();
      $bats[] = $bat;
    }
    $planete->batiments = $bats;

    $planete->proprietaire = $this;

    $ge->persist($planete);

    // On ajoute les technologies au joueur
    $technologies  = $ge->select('Technologie', array(), $ge::AUCUN)->getAll();
    $techs = array();
    foreach($technologies as $technologie){
      $tech = clone $technologie;
      $tech->niveau = 0;
      $tech->date_recherche = new DateTime();
      $techs[] = $tech;
    }
    $this->technologies = $techs;

    $ge->persist($this);
  }


  public function getRechercheEnCours(){
    if(empty($this->technologies)){
      return false;
    }
    foreach($this->technologies as $technologie){
      if(!is_null($technologie->date_amelioration)){
        return $technologie;
      }
    }
    return null;
  }

  public function recherchePossible(){
    if(empty($this->technologies)){
      return true;
    }
    foreach($this->technologies as $technologie){
      if(!is_null($technologie->date_amelioration)){ // Si une recherche est déjà en cours
        return false;
      }
    }
    return true;
  }
}

?>
