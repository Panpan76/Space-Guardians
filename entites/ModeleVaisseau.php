<?php

class ModeleVaisseau extends EntiteMere{
  protected $id;
  protected $nom;

  protected $joueur;
  protected $classe;
  protected $temps;

  protected $ressources;

  protected $couts;

  public function postSelect(){
    $ress = array();
    foreach($this->ressources as $ressource){
      $ress[$ressource->id] = $ressource->quantite;
    }
    $this->couts = $ress;
  }
}
