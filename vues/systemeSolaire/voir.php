<canvas id="canvasSysteme" width="500" height="500" style="border:1px solid #000000; background: black;"></canvas>

<script type="text/javascript">

var canvasSysteme = document.getElementById("canvasSysteme");

</script>

<?php
// Centrer sur le système solaire
  echo '<script type="text/javascript">';
    $centreX = $systeme->centreX;
    $centreY = $systeme->centreY;
    // Le système solaire
    echo "dessinePlanete(canvasSysteme, 250, 250, 20, 'yellow');";
    foreach($systeme->planetes as $planete){
      $couleur = 'red';
      if($planete == $planeteSelect){
        $couleur = 'purple';
      }
      echo "dessinePlanete(canvasSysteme, $planete->x-$centreX+250, $planete->y-$centreY+250, 20, '$couleur');";
    }
  echo '</script>';

?>
