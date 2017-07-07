<?php
require_once DOSSIER_VUES.'/base.php';

?>
<?= $message; ?>

<form action="" method="post">
  <table>
    <tr>
      <td>Pseudo</td>
      <td><input type="text" name="pseudo" value="<?= $joueur->pseudo; ?>"/></td>
    </tr>
    <tr></tr>
    <tr>
      <td>Mot de passe</td>
      <td><input type="password" name="mdp" /></td>
    </tr>
    <tr></tr>
    <tr>
      <td>Race</td>
      <td>
        <?php
        foreach($races as $race){
          echo "<input type='radio' name='race' value='$race->id' /> $race->nom<br />";
        }
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"><button type="submit">S'inscrire</button></td>
    </tr>
  </table>
</form>
