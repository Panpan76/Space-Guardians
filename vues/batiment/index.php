<?php
require_once DOSSIER_VUES.'/base.php';


$disabled = '';
if(!$planete->constructionPossible()){
  $disabled = 'disabled';
?>

  <div class="construction">
    <?php
    $batimentConstruction = $planete->getBatimentEnConstruction();
    ?>

    <div class="nom"><?= $batimentConstruction->nom.' ('.($batimentConstruction->niveau+1) .')'; ?></div>
     - 
    <div class="tempsRestant">
      <span class="nbSec"><?= $batimentConstruction->tempsRestant(); ?></span>
      <span class="affichageRestant"><?= convertirSecondes($batimentConstruction->tempsRestant()); ?></span>
    </div>

  </div>
<?php
}

foreach($batiments as $batiment){
  $type = $batiment->typeBatiment->nom;
  $str = "<div class='batiment'>
            <div class='image'>$batiment->image</div>
            <div class='nom'>$batiment->nom ($batiment->niveau) - $type</div>
            <div class='description'>$batiment->description</div>";

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
            foreach($batiment->$donnees as $idRessource => $donnee){
              $str .= $donnee.' '.$batiment->ressources[$idRessource]->nom.'<br />';
            }
            $batiment->niveau++;
            $batiment->calculProduction();
      $str .= "</td>
              <td>";
            foreach($batiment->$donnees as $idRessource => $donnee){
              $str .= $donnee.' '.$batiment->ressources[$idRessource]->nom.'<br />';
            }
            $batiment->niveau--;
    $str .= "</td>
          </tr>
        </table>";



  $str .= "<div class='couts'>Coûts : ";
  foreach($batiment->couts as $idRessource => $cout){
    $str .= $cout.' '.$batiment->ressources[$idRessource]->nom.' / ';
  }
  $str = substr($str, 0, -3);
  $lien = lien('batiment/construire/'.$batiment->id);
  $temps = convertirSecondes($batiment->tempsConstruction);

  $str .= "<a class='construire bouton $disabled right' href='$lien'>Construire ($temps)</a>
          </div>
        </div>";

  echo $str;
}
?>

<script type="text/javascript">

$(document).ready(function(){
  if(typeof $('.tempsRestant') != 'undefined'){
    var updateTemps = setInterval(function(){
      var nb = parseInt($('.tempsRestant>.nbSec').text());
      if(nb <= 0){
        location.reload();
      }
      nb--;
      $('.tempsRestant>.nbSec').text(nb);
      $('.tempsRestant>.affichageRestant').text(convertirSecondes(nb));
    }, 1000);
  }
});


function convertirSecondes(sec){
  var unites = ['s', 'm', 'h'];
  var res = [];
  var n = 0;
  while(sec > 0){
    res[n] = sec % 60;
    sec = Math.floor(sec / 60);
    n++;
  }
  str = '';
  for(var i = n-1; i >= 0; i--){
    str += res[i]+unites[i]+' ';
  }
  str = str.substr(0, str.length-1);
  return str;
}

</script>
