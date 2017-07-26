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
    $this->refreshBatiment();

    $res = array();
    foreach($this->stocks as $ressource){
      $res[$ressource->id] = clone $ressource;
    }
    $this->stocks = $res;

    $this->initProduction();
    $this->initStockage();
  }


  private function refreshBatiment(){
    if(($batiment = $this->getBatimentEnConstruction()) != null){
      if($batiment->tempsRestant() <= 0){
        $batiment->niveau++;
        $batiment->date_construction = $batiment->date_amelioration;
        $batiment->date_amelioration = null;
        // $batiment->postSelect();
        $ge = GestionnaireEntite::getInstance();
        $ge->persist($this);
        $batiment->calculCouts();
      }
    }
  }

  private function initProduction(){
    foreach($this->batiments as $batiment){
      if(is_object($batiment->typeBatiment) && $batiment->typeBatiment->nom == 'Production'){
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
  }

  private function initStockage(){
    foreach($this->batiments as $batiment){
      if(is_object($batiment->typeBatiment) && $batiment->typeBatiment->nom == 'Stockage'){
        foreach($batiment->stockages as $idRessource => $stockage){
          $this->stocks[$idRessource]->quantite = min($this->stocks[$idRessource]->quantite, $stockage[$idRessource]->quantite);
          $this->ressources[$idRessource]['quantite'] = $this->stocks[$idRessource]->quantite;
          $this->ressources[$idRessource]['max']      = $stockage[$idRessource]->quantite;
        }
      }
    }
  }

  /**
   * Met à jour le stock de ressource de la planète selon des ressources à soustraire
   *
   * @param array $couts
   *    $couts = array(
   *      idRessource => quantite,
   *    )
   *
   * @return boolean
   */
  public function majStock($couts){
    foreach($couts as $idRessource => $cout){
      if($this->stocks[$idRessource]->quantite > $cout){
        // On recalcul les stocks en soustrayant le cout du batiment
        $stock = $this->stocks[$idRessource];
        $stock->quantite -= $cout;
      }
      else{
        // Si une ressource est insuffisante, on annule
        return false;
      }
    }

    $ge = GestionnaireEntite::getInstance();

    // On met à jour la date de création du batiment pour qu'il produise à partir de maintenant
    foreach($this->batiments as $batiment){
      $batiment->date_construction = new DateTime();
    }

    return $ge->persist($this);
  }


  public function getBatimentEnConstruction(){
    if(empty($this->batiments)){
      return false;
    }
    foreach($this->batiments as $batiment){
      if(!is_null($batiment->date_amelioration)){
        return $batiment;
      }
    }
    return null;
  }

  public function constructionPossible(){
    if(empty($this->batiments)){
      return false;
    }
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
