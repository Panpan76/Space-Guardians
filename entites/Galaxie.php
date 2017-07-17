<?php
/**
 * Galaxie
 *
 * @author Panpan76
 * @version 1.0
 */
class Galaxie extends EntiteMere{

  #################
  ### Variables ###
  #################

  protected $id;
  protected $nom;
  protected $largeur;
  protected $hauteur;
  protected $systemesSolaires = array();

  public function postInsert(){
    $ge = GestionnaireEntite::getInstance();

    $limite = SystemeSolaire::MAX_RAYON;

    // On part du centre de la galaxie
    $x = ceil($this->largeur/2);
    $y = ceil($this->hauteur/2);
    $coordonnees = array('x', 'y');

    // Grille pour la génération
    $grille = array();
    for($i = 0; $i < $this->largeur; $i++){
      for($j = 0; $j < $this->hauteur; $j++){
        $grille[$i][$j] = 0;
      }
    }


    $eloignement = 1;
    $signe = 1;
    $nb = 0;
    while($x > $limite && $x < $this->largeur - $limite && $y > $limite && $y < $this->hauteur - $limite){

      foreach($coordonnees as $direction){
        for($i = 0; $i < $eloignement; $i++){
          $$direction += $signe;

          $rayonMin = min(SystemeSolaire::MAX_RAYON, SystemeSolaire::MIN_RAYON + floor($eloignement/(SystemeSolaire::MAX_RAYON*25)));
          $rayonMax = max(SystemeSolaire::MIN_RAYON, SystemeSolaire::MAX_RAYON - floor(SystemeSolaire::MAX_RAYON*25/$eloignement));
          $rayon = rand($rayonMin, $rayonMax);
          if(rand(0, floor($eloignement*2/10)) == 0 && $this->peutSysteme($x, $y, $rayon, $grille)){
            $systeme = new SystemeSolaire();
            $systeme->nom = 'Système solaire';
            $systeme->galaxie = $this;
            $systeme->centreX = $x;
            $systeme->centreY = $y;
            $systeme->rayon = $rayon;
            if($ge->persist($systeme)){
              $this->systemesSolaires[] = $systeme;
              $nb++;
              $this->remplirEspace($systeme, $grille);
              echo "-----Un système solaire créé ! (#$nb $x:$y)-----<br />";
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

  private function peutSysteme($x, $y, $rayon, $grille){
    for($i = $x - $rayon-2; $i < $x + $rayon+2; $i++){
      for($j = $y - $rayon-2; $j < $y + $rayon+2; $j++){
        if($grille[$i][$j] == 1){
          return false;
        }
      }
    }
    return true;

    // foreach($this->systemesSolaires as $systemeSolaire){
    //   if($x > $systemeSolaire->centreX - (2*$systemeSolaire->rayon) && $x < $systemeSolaire->centreX + (2*$systemeSolaire->rayon) && $y > $systemeSolaire->centreY - (2*$systemeSolaire->rayon) && $y < $systemeSolaire->centreY + (2*$systemeSolaire->rayon)){
    //     return false;
    //   }
    // }
    // return true;
  }

  private function remplirEspace($systeme, &$grille){
    for($i = $systeme->centreX - $systeme->rayon; $i < $systeme->centreX + $systeme->rayon; $i++){
      for($j = $systeme->centreY - $systeme->rayon; $j < $systeme->centreY + $systeme->rayon; $j++){
        $grille[$i][$j] = 1;
      }
    }
  }
}

?>
