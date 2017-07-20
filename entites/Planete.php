<?php
/**
 * Planete
 *
 * @author Panpan76
 * @version 1.0
 */
class Planete extends EntiteMere{

  #################
  ### Variables ###
  #################

  protected $id;
  protected $nom;
  protected $systemeSolaire;
  protected $x;
  protected $y;
  protected $proprietaire;
  protected $batiments;

  protected $stocks;

  protected $ressources;

  public function postSelect(){

    $res = array();
    foreach($this->stocks as $ressource){
      $res[$ressource->id] = $ressource;
    }
    $this->stocks = $res;

    foreach($this->batiments as $batiment){
      if($batiment->typeBatiment->nom == 'Production'){
        foreach($batiment->productions as $idRessource => $production){
          $prodSec = $production / 3600; // On converti la prod / heure en prod / sec
          $this->stocks[$idRessource]->quantite += $prodSec * (time() - $batiment->date_construction->getTimestamp()); // On ajoute la prod au stock existant
          $this->ressources[$idRessource] = array(
            'nom'     => $res[$ressource->id]->nom,
            'prod'    => $production,
            'prodSec' => $production / 3600,
            'quantite'=> $this->stocks[$idRessource]->quantite,
            'max'     => null
          );
        }
      }
    }

    foreach($this->batiments as $batiment){
      if($batiment->typeBatiment->nom == 'Stockage'){
        foreach($batiment->stockages as $idRessource => $stockage){
          $this->stocks[$idRessource]->quantite = min($this->stocks[$idRessource]->quantite, $stockage[$idRessource]->quantite);
          $this->ressources[$idRessource]['quantite'] = $this->stocks[$idRessource]->quantite;
          $this->ressources[$idRessource]['max']      = $stockage[$idRessource]->quantite;
        }
      }
    }

    if(($batiment = $this->getBatimentEnConstruction()) != null){
      if($batiment->tempsRestant() <= 0){
        $batiment->niveau++;
        $batiment->date_construction = new DateTime();
        $batiment->date_amelioration = null;
        $ge = GestionnaireEntite::getInstance();
        $ge->persist($this);
        $batiment->postSelect();
      }
    }
  }


  public function getBatimentEnConstruction(){
    foreach($this->batiments as $batiment){
      if(!is_null($batiment->date_amelioration)){
        return $batiment;
      }
    }
    return null;
  }

  public function constructionPossible(){
    foreach($this->batiments as $batiment){
      if(!is_null($batiment->date_amelioration)){ // Si une construction est déjà en cours
        return false;
      }
    }
    return true;
  }

  public function __tostring(){
    $systeme = $this->systemeSolaire;
    return "$this->nom ($this->x:$this->y:$systeme->id)";
  }


}

?>
