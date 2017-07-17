<?php
/**
 * SystemeSolaire
 *
 * @author Panpan76
 * @version 1.0
 */
class SystemeSolaire extends EntiteMere{

  #################
  ### Variables ###
  #################

  const MIN_RAYON = 3;
  const MAX_RAYON = 20;

  protected $id;
  protected $nom;
  protected $galaxie;
  protected $centreX;
  protected $centreY;
  protected $rayon;
  protected $planetes = array();
  protected $nbPlanetes;


  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();

    // On part du centre du système solaire
    $x = $this->centreX;
    $y = $this->centreY;
    $coordonnees = array('x', 'y');

    $eloignement = 1;
    $signe = 1;
    while($x > $this->centreX - $this->rayon && $x < $this->centreX + $this->rayon && $y > $this->centreY - $this->rayon && $y < $this->centreY + $this->rayon){

      foreach($coordonnees as $direction){
        for($i = 0; $i < $eloignement; $i++){
          $$direction += $signe;
          if(rand(0, floor($eloignement*2)) == 0 && $this->peutPlanete($x, $y, $eloignement)){
            $planete = new Planete();
            $planete->nom = 'Planète';
            $planete->systemeSolaire = $this;
            $planete->x = $x;
            $planete->y = $y;
            if($ge->persist($planete)){
              $this->planetes[] = $planete;
              $nb = count($this->planetes);
              echo "    Une planète créée ! ($nb)<br />";
              ob_flush();
              flush();
            }
          }
        }
      }

      $eloignement++;
      $signe *= -1; // On inverse le signe
    }
  }

  public function postSelect(){
    $ge = GestionnaireEntite::getInstance();

    $this->nbPlanetes = $ge->count('Planete', array('systemeSolaire' => $this->id));
  }


  private function peutPlanete($x, $y, $eloignement){
    foreach($this->planetes as $planete){
      if($x > $planete->x - max(2, $eloignement/5) && $x < $planete->x + max(2, $eloignement/5) && $y > $planete->y - max(2, $eloignement/5) && $y < $planete->y + max(2, $eloignement/5)){
        return false;
      }
    }
    return true;
  }
}

?>
