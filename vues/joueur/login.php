<?php
require_once DOSSIER_VUES.'/base.php';

?>
<?= $message; ?>
<form action="" method="post">
  <table>
    <tr>
      <td>Pseudo</td>
      <td><input type="text" name="pseudo" /></td>
    </tr>
    <tr></tr>
    <tr>
      <td>Mot de passe</td>
      <td><input type="password" name="mdp" /></td>
    </tr>
    <tr>
      <td colspan="2"><button type="submit">Se connecter</button></td>
    </tr>
  </table>
</form>
<a href="<?= lien('inscription'); ?>">Pas encore inscrit ?</a>
