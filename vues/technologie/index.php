<?php
require_once DOSSIER_VUES.'/base.php';

$disabled = '';
if(!$joueur->recherchePossible()){
  $disabled = 'disabled';
?>

  <div class="recherche">
    <?php
    $technologieRecherche = $joueur->getRechercheEnCours();
    ?>

    <div class="nom"><?= $technologieRecherche->nom.' ('.($technologieRecherche->niveau+1) .')'; ?></div>
     -
    <div class="tempsRestant">
      <span class="nbSec"><?= $technologieRecherche->tempsRestant(); ?></span>
      <span class="affichageRestant"><?= convertirSecondes($technologieRecherche->tempsRestant()); ?></span>
    </div>

  </div>
<?php
}

foreach($technologies as $technologie){
  $type = $technologie->typeTechnologie->nom;
  $str = "<div class='batiment'>
            <div class='image'>$technologie->image</div>
            <div class='nom'>$technologie->nom ($technologie->niveau) - $type</div>
            <div class='description'>$technologie->description</div>";

  $str .= "<div class='couts'>Coûts : ";
  foreach($technologie->couts as $idRessource => $cout){
    $str .= $cout.' '.$technologie->ressources[$idRessource]->nom.' / ';
  }
  $str = substr($str, 0, -3);
  $lien = lien('technologie/rechercher/'.$technologie->id);
  $temps = convertirSecondes($technologie->tempsRecherche);

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
