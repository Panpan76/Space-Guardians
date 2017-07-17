<div class="menu header">
  <div class="left">
    <div><a href="<?= lien(''); ?>"><img src="images/home.png" /></a></div>
    <div class="deroulant"><a href="<?= lien("joueur/$joueur->id"); ?>"><?= $joueur->pseudo; ?></a>
      <div class="cache">
        <div><a href="<?= lien('deconnexion'); ?>">Déconnexion</a></div>
      </div>
    </div>
    <div><?= $planete; ?></div>
  </div>
</div>
