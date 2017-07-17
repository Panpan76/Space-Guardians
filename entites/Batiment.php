<?php

class Batiment extends EntiteMere{
  protected $id;
  protected $typeBatiment;
  protected $nom;
  protected $description;
  protected $image;
  protected $temps;
  protected $niveau;

  protected $ressources;

  protected $couts;
  protected $productions;
  protected $stockages;

  public function postSelect(){
    $this->calculCouts();
    $this->calculTempsConstruction();

    switch($this->typeBatiment->nom){
      case 'Production':
        $this->calculProduction();
        break;

      case 'Stockage':
        $this->calculStockage();
        break;
    }
  }

  private function calculCouts(){
    foreach($this->ressources as $ressource){
      $this->couts[$ressource->id] = ($this->niveau+1)*$ressource->quantite;
    }
  }

  private function calculProduction(){
    foreach($this->ressources as $ressource){
      $this->productions[$ressource->id] = ($this->niveau+1)*$ressource->quantite*3/2*$ressource->coefficient;
    }
  }

  private function calculStockage(){
    foreach($this->ressources as $ressource){
      $this->stockages[$ressource->id] = ($this->niveau+1)*$ressource->quantite*3/2*$ressource->coefficient;
    }
  }

  private function calculTempsConstruction(){
    $this->temps = ($this->niveau+1)*$this->temps*3/2;
  }
}
