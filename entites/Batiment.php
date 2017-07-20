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

  protected $ressources;

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

    $res = array();
    foreach($this->ressources as $ressource){
      $res[$ressource->id] = $ressource;
    }
    $this->ressources = $res;

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

  public function calculProduction(){
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
    $this->tempsConstruction = ($this->niveau+1)*$this->temps*3/2;
  }

  public function tempsRestant(){
    $maintenant = new DateTime();
    $restant = $this->date_amelioration->getTimestamp() - $maintenant->getTimestamp();
    return $restant; // En seconde
  }

  public function __tostring(){
    $type = $this->typeBatiment->nom;
    $str = "<div class='batiment'>
              <div class='image'>$this->image</div>
              <div class='nom'>$this->nom ($this->niveau) - $type</div>
              <div class='description'>$this->description</div>";

    $typeArray = array(
      'Production' => array(
        'class'   => 'production',
        'donnees' => 'productions'
      ),
      'Stockage' => array(
        'class'   => 'stockage',
        'donnees' => 'stockages'
      )
    );

    $class    = $typeArray[$type]['class'];
    $donnees  = $typeArray[$type]['donnees'];

    $str .= "<table class='$class'>
              <tr>
                <th>Actuelle</th>
                <th>Niveau suivant</th>
              </tr>
              <tr>
                <td>";
              foreach($this->$donnees as $idRessource => $donnee){
                $str .= $donnee.' '.$this->ressources[$idRessource]->nom.'<br />';
              }
              $this->niveau++;
              $this->calculProduction();
        $str .= "</td>
                <td>";
              foreach($this->$donnees as $idRessource => $donnee){
                $str .= $donnee.' '.$this->ressources[$idRessource]->nom.'<br />';
              }
              $this->niveau--;
      $str .= "</td>
            </tr>
          </table>";



    $str .= "<div class='couts'>Coûts : ";
    foreach($this->couts as $idRessource => $cout){
      $str .= $cout.' '.$this->ressources[$idRessource]->nom.' / ';
    }
    $str = substr($str, 0, -3);
    $lien = lien('batiment/construire/'.$this->id);
    $temps = convertirSecondes($this->tempsConstruction);
    $str .= "<a class='construire bouton disabled right' href='$lien'>Construire ($temps)</a>
            </div>
          </div>";
    return $str;
  }
}
