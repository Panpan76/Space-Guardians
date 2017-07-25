<?php
require_once DOSSIER_VUES.'/base.php';

?>

<?= $message; ?>

<form action="" method="post">
  <table>
    <tr>
      <td>Nom</td>
      <td><input type="text" name="nom" value="<?= $alliance->nom; ?>"/></td>
    </tr>
    <tr></tr>
    <tr>
      <td>TAG</td>
      <td>[<input type="text" name="tag" value="<?= $alliance->tag; ?>"/>]</td>
    </tr>
    <tr></tr>
    <tr>
      <td>Description</td>
      <td><textarea name="description"><?= $alliance->description; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="2"><button type="submit">Créer</button></td>
    </tr>
  </table>
</form>
