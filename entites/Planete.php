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

  public function __tostring(){
    $systeme = $this->systemeSolaire;
    return "$this->nom ($this->x:$this->y:$systeme->id)";
  }
}

?>
