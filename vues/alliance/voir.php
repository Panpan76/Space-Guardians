<?php
require_once DOSSIER_VUES.'/base.php';

?>
<center><h2><?= $alliance->nom; ?> [<?= $alliance->tag; ?>]</h2></center>

<div class="membres">
<?php
foreach($alliance->joueurs as $joueur){
  $rang = $joueur->rang->nom;
  echo "<div>$joueur->pseudo ($rang)</div>";
}
?>
</div>
