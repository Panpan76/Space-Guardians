<?php
require_once DOSSIER_VUES.'/base.php';

?>

<canvas id="canvas" width="2000" height="2000" style="border:1px solid #000000; background: black;">

</canvas>

<script type="text/javascript">

var canvas = document.getElementById("canvas");
var canvasWidth = canvas.width;
var canvasHeight = canvas.height;
var ctx = canvas.getContext("2d");

var couleurs = <?= json_encode($degrades); ?>;

function dessineSysteme(x, y, r, color){
  ctx.strokeStyle = color;
  ctx.beginPath();
  ctx.arc(x, y, r, 0, 2*Math.PI);
  ctx.stroke();
  drawPixel(x, y, 1, 'yellow');
}

function drawPixel(x, y, r, color){
  ctx.fillStyle = color;
  // ctx.fillRect(x-5, y-5, 10, 10);
  ctx.beginPath();
  ctx.arc(x, y, r, 0, 2*Math.PI);
  ctx.fill();
    // var index = (x + y * canvasWidth) * 4;
    //
    // canvasData.data[index + 0] = r;
    // canvasData.data[index + 1] = g;
    // canvasData.data[index + 2] = b;
    // canvasData.data[index + 3] = a;
}

// Le centre
drawPixel(1000, 1000, 20, 'purple');

// getSysteme(1);
// function getSysteme(n){
//   $.get('<?= lien('systemeSolaire/json/'); ?>'+n, function(data){
//     systeme = JSON.parse(data);
//     console.log(systeme);
//     var couleur = couleurs[systeme.planetes.length];
//     drawPixel(systeme.centreX, systeme.centreY, systeme.rayon, couleur);
//   });
//   n++;
//   if(n < 3942){
//     setTimeout(function(){
//       getSysteme(n);
//     }, 10);
//   }
// }
// for(var i = 1; i < 682; i++){
//   $.get('<?= lien('systemeSolaire/json/'); ?>'+i, function(data){
//     systeme = JSON.parse(data);
//     console.log(systeme);
//     var couleur = couleurs[systeme.planetes.length];
//     drawPixel(systeme.centreX, systeme.centreY, systeme.rayon, couleur);
//   });
// }

</script>

<?php
  echo '<script type="text/javascript">';
  foreach($systemes as $systeme){
    $couleur = $degrades[$systeme->nbPlanetes];
    // echo "drawPixel($systeme->centreX, $systeme->centreY, $systeme->rayon, '$couleur');";
    echo "dessineSysteme($systeme->centreX, $systeme->centreY, $systeme->rayon, '$couleur');";
  }
  echo '</script>';
?>
