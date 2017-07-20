<?php

class Technologie extends EntiteMere{
  protected $id;
  protected $typeTechnologie;
  protected $nom;
  protected $description;
  protected $image;
  protected $temps;
  protected $niveau;

  protected $date_recherche;
  protected $date_amelioration;

  protected $ressources;

  protected $tempsRecherche;
  protected $couts;

  public function postSelect(){
    $this->calculCouts();
    $this->calculTempsRecherche();

    if(!is_object($this->date_recherche)){
      $this->date_recherche = new DateTime($this->date_recherche);
    }
    if(!is_null($this->date_amelioration) && !is_object($this->date_amelioration)){
      $this->date_amelioration = new DateTime($this->date_amelioration);
    }

    $res = array();
    foreach($this->ressources as $ressource){
      $res[$ressource->id] = $ressource;
    }
    $this->ressources = $res;
  }

  private function calculCouts(){
    foreach($this->ressources as $ressource){
      $this->couts[$ressource->id] = ($this->niveau+1)*$ressource->quantite;
    }
  }

  private function calculTempsRecherche(){
    $this->tempsRecherche = ($this->niveau+1)*$this->temps*3/2;
  }

  public function tempsRestant(){
    $maintenant = new DateTime();
    $restant = $this->date_amelioration->getTimestamp() - $maintenant->getTimestamp();
    return $restant; // En seconde
  }

}
