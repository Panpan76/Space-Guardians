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


  public function postSelect(){


    if(($technologie = $this->getRechercheEnCours()) != null){
      if($technologie->tempsRestant() <= 0){
        $technologie->niveau++;
        $technologie->date_recherche = new DateTime();
        $technologie->date_amelioration = null;
        $ge = GestionnaireEntite::getInstance();
        $ge->persist($this);
        $technologie->postSelect();
      }
    }
  }

  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();
    $planete = $ge->select('Planete', array('proprietaire' => 'NULL'), GestionnaireEntite::PARENTS, true, 1)->aleatoire()->getOne();

    $planete->proprietaire = $this;

    $ge->persist($planete);
  }


  public function getRechercheEnCours(){
    foreach($this->technologies as $technologie){
      if(!is_null($technologie->date_amelioration)){
        return $technologie;
      }
    }
    return null;
  }

  public function recherchePossible(){
    foreach($this->technologies as $technologie){
      if(!is_null($technologie->date_amelioration)){ // Si une recherche est déjà en cours
        return false;
      }
    }
    return true;
  }
}

?>
