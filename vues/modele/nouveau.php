<?php
require_once DOSSIER_VUES.'/base.php';

?>

<?= $message; ?>

<form action="" method="post">
  <table>
    <tr>
      <td>Nom</td>
      <td><input type="text" name="nom" value="<?= $modele->nom; ?>"/></td>
    </tr>
    <tr></tr>
    <?php
    foreach($ressources as $ressource){
      echo "<tr>
              <td>$ressource->nom</td>
              <td><input type='range' name='ressource_$ressource->id' min='0' max='10000' value='0' step='10'/> <span class='valeur'>0</span></td>
            </tr>";
    }
     ?>
    <tr>
      <td colspan="2"><button type="submit">Créer</button></td>
    </tr>
  </table>
</form>

<script type="text/javascript">
$(document).ready(function(){
  $('input[type="range"]').change(function(){
    $(this).next('span.valeur').text($(this).val());
  });
});
</script>
