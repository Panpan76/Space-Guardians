<?php
require_once DOSSIER_VUES.'/base.php';


var_dump($alliances);
?>
<a href="<?= lien('alliance/nouvelle'); ?>"><div class="bouton">Créer une alliance</div></a>

<table>
  <tr>
    <th>Nom</th>
    <th>Nombre de membre</th>
    <th>Actions</th>
  </tr>
<?php

foreach($alliances as $alliance){
  echo "<tr>
          <td>$alliance->nom [$alliance->tag]</td>
          <td></td>
          <td></td>
        </tr>";
}

?>
</table>
