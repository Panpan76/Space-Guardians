<?php
require_once 'base.php';

// var_dump($joueur);

?>

Liste des planètes :<br />
<?php

foreach($joueur->planetes as $planete){
  $systeme = $planete->systemeSolaire;
  echo "$planete->nom ($planete->x:$planete->y:$systeme->id)<br />";
}

?>
