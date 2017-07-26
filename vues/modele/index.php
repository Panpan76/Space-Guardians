<?php
require_once DOSSIER_VUES.'/base.php';


?>
<a href="<?= lien('modele/nouveau'); ?>"><div class="bouton">Créer un modèle</div></a>

<table>
  <tr>
    <th>Nom</th>
    <th>Coûts</th>
    <th>Actions</th>
<?php
foreach($modeles as $modele){
  echo "<tr>
          <td>$modele->nom</td>
          <td>";
  foreach($modele->ressources as $ressource){
    echo "$ressource->quantite $ressource->nom<br />";
  }
  $lien = lien("modele/$modele->id/construire/");
  $temps = convertirSecondes($modele->temps);
  echo      "Temps de construction : $temps
          </td>
          <td>
            <input type='number' min='1' value='1' step='1'/>
            <a href='$lien' class='construire'>
              <div class='bouton'>Construire</div>
            </a>
          </td>
        </tr>";
}
?>
</table>

<script type="text/javascript">

$(document).ready(function(){
  $('a.construire').click(function(){
    console.log($(this).prev('input').val());
    $(this).attr('href', $(this).attr('href')+$(this).prev('input').val());
    return true;
  });
});

</script>
