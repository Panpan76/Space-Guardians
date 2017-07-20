<div class="menu header">
  <div class="left">
    <div><a href="<?= lien(''); ?>"><img src="images/home.png" /></a></div>
    <div class="deroulant"><a href="<?= lien("joueur/$joueur->id"); ?>"><?= $joueur->pseudo; ?></a>
      <div class="cache">
        <div><a href="<?= lien('deconnexion'); ?>">Déconnexion</a></div>
      </div>
    </div>
    <div><a href="<?= lien("planete/$planete->id"); ?>"><?= $planete; ?></a></div>
  </div>
  <div class="right">
    <?php
    foreach($planete->ressources as $idRessource => $ressource){
      $quantiteExacte = $ressource['quantite'];
      $quantite = number_format(floor($ressource['quantite']), 0, '', ' ');
      $prodSec = $ressource['prodSec'];
      $nom = $ressource['nom'];
      echo "<div class='ressource'>
              <span class='quantiteExacte'>$quantiteExacte</span>
              <span class='prodParSeconde'>$prodSec</span>
              <span class='quantite'>$quantite</span>
              $nom
            </div>";
    }
    ?>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
  var updateRessources = setInterval(function(){
    $('.ressource').each(function(){
      var quantiteExacte = parseFloat($(this).children('.quantiteExacte').text());
      var prodParSeconde = parseFloat($(this).children('.prodParSeconde').text());
      quantiteExacte += prodParSeconde;
      $(this).children('.quantiteExacte').text(quantiteExacte);
      $(this).children('.quantite').text(formatNb(quantiteExacte));
    });
  }, 1000);
});

function formatNb(nb){
  var nombre = parseInt(nb.toString().split('.')[0]);
  var str = '';
  while(nombre >= 1000){
    str = (nombre % 1000)+' '+str;
    nombre = parseInt(nombre / 1000);
  }
  str = (nombre % 1000)+' '+str;
  return str;
}

</script>
