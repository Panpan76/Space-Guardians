<?php
require_once DOSSIER_VUES.'/base.php';

?>

<script type="text/javascript">

function dessineSysteme(canvas, x, y, r, color){
var ctx = canvas.getContext('2d');
ctx.strokeStyle = color;
ctx.beginPath();
ctx.arc(x, y, r, 0, 2*Math.PI);
ctx.stroke();
drawPixel(canvas, x, y, 1, 'yellow');
}

function drawPixel(canvas, x, y, r, color){
var ctx = canvas.getContext('2d');
ctx.fillStyle = color;
// ctx.fillRect(x-5, y-5, 10, 10);
ctx.beginPath();
ctx.arc(x, y, r, 0, 2*Math.PI);
ctx.fill();
}
function dessinePlanete(canvas, x, y, coef, color){
var ctx = canvas.getContext('2d');
ctx.fillStyle = color;
ctx.beginPath();
var milieu = 250;
var nouvX = (milieu-x)*coef+x;
var nouvY = (milieu-y)*coef+y;
ctx.arc(nouvX, nouvY, 10, 0, 2*Math.PI);
ctx.fill();
}

</script>

<table>
  <tr>
    <th colspan="2">
      Liste des planètes :
      <select name="planete">
      <?php

        foreach($joueur->planetes as $planeteListe){
          echo "<option>$planeteListe</option>";
        }
      ?>
      </select>
    </th>
  </tr>
  <tr>
    <td><?= ControlleurGalaxie::voir($galaxie->id); ?></td>
    <td><?= ControlleurSystemeSolaire::voir($planete->systemeSolaire->id); ?></td>
  </tr>
</table>
