<?php
/**
 * Classe GestionnaireEntiteResultats
 *
 * Permet de gérer les résultats des requêtes du gestionnaire d'entités
 *
 * @author  Panpan76
 * @version 1.0
 */
class GestionnaireEntiteResultats{

  private $resultats;

  public function __construct($resultats = array()){
    $this->resultats = $resultats;
  }


  public function aleatoire(){
    shuffle($this->resultats);
    return $this;
  }

  public function getOne($id = 0){
    if(!isset($this->resultats[$id])){
      return null;
    }
    return $this->resultats[$id];
  }

  public function getAll(){
    return $this->resultats;
  }
}

?>
