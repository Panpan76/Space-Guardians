<?php

class Batiment extends EntiteMere{
  protected $id;
  protected $typeBatiment;
  protected $nom;
  protected $description;
  protected $image;
  protected $temps;
  protected $niveau;

  protected $date_construction;
  protected $date_amelioration;

  // protected $ressources;

  protected $ressourcesBase;
  protected $tempsConstruction;
  protected $couts;
  protected $productions;
  protected $stockages;

  public function postSelect(){
    $this->calculCouts();
    $this->calculTempsConstruction();

    if(!is_object($this->date_construction)){
      $this->date_construction = new DateTime($this->date_construction);
    }
    if(!is_null($this->date_amelioration) && !is_object($this->date_amelioration)){
      $this->date_amelioration = new DateTime($this->date_amelioration);
    }

    if(is_array($this->ressourcesBase)){
      $res = array();
      foreach($this->ressourcesBase as $ressource){
        $res[$ressource->id] = clone $ressource;
      }
      $this->ressourcesBase = $res;
    }
  }

  public function calculCouts(){
    if(is_array($this->ressourcesBase)){
      foreach($this->ressourcesBase as $ressource){
        $this->couts[$ressource->id] = ($this->niveau+1)*$ressource->quantite;
      }
    }

    if(is_object($this->typeBatiment)){
      switch($this->typeBatiment->nom){
        case 'Production':
          $this->calculProduction();
          break;

        case 'Stockage':
          $this->calculStockage();
          break;
      }
    }
  }

  public function calculProduction(){
    if(is_array($this->ressourcesBase)){
      foreach($this->ressourcesBase as $ressource){
        $this->productions[$ressource->id] = ($this->niveau+1)*$ressource->quantite*3/2*$ressource->coefficient;
      }
    }
  }

  private function calculStockage(){
    if(is_array($this->ressourcesBase)){
      foreach($this->ressourcesBase as $ressource){
        $this->stockages[$ressource->id] = ($this->niveau+1)*$ressource->quantite*3/2*$ressource->coefficient;
      }
    }
  }

  private function calculTempsConstruction(){
    $this->tempsConstruction = ($this->niveau+1)*$this->temps*3/2;
  }

  public function tempsRestant(){
    $maintenant = new DateTime();
    $restant = $this->date_amelioration->getTimestamp() - $maintenant->getTimestamp();
    return $restant; // En seconde
  }
}
