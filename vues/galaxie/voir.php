<canvas id="canvas" width="500" height="500" style="border:1px solid #000000; background: black;"></canvas>

<script type="text/javascript">

var canvas = document.getElementById("canvas");

</script>

<?php
// Centrer sur le système solaire
  echo '<script type="text/javascript">';
  foreach($systemes as $systeme){
    foreach($joueur->planetes as $planete){
      $centreX = $planete->systemeSolaire->centreX;
      $centreY = $planete->systemeSolaire->centreY;
      if($systeme->centreX > $centreX -150 && $systeme->centreX < $centreX +150 && $systeme->centreY > $centreY -150 && $systeme->centreY < $centreY +150){
        $couleur = $degrades[$systeme->nbPlanetes];
        // echo "drawPixel($systeme->centreX, $systeme->centreY, $systeme->rayon, '$couleur');";
        if($planete->systemeSolaire == $systeme){
          $couleur = 'purple';
        }
        echo "dessineSysteme(canvas, $systeme->centreX-$centreX+250, $systeme->centreY-$centreY+250, $systeme->rayon, '$couleur');";

      }
    }
  }
  echo '</script>';

// Pour toute la galaxie
  // echo '<script type="text/javascript">';
  // foreach($systemes as $systeme){
  //   $couleur = $degrades[$systeme->nbPlanetes];
  //   // echo "drawPixel($systeme->centreX, $systeme->centreY, $systeme->rayon, '$couleur');";
  //   echo "dessineSysteme($systeme->centreX, $systeme->centreY, $systeme->rayon, '$couleur');";
  // }
  // echo '</script>';
?>
