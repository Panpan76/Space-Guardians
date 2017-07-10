<?php

$routes = array(
  '' => array(
    'controlleur' => 'ControlleurDefaut',
    'methode'     => 'index'
  ),
  'joueur\/([^\/]+)' => array(
    'controlleur' => 'ControlleurJoueur',
    'methode'     => 'voir'
  ),
  'login' => array(
    'controlleur' => 'ControlleurJoueur',
    'methode'     => 'login'
  ),
  'inscription' => array(
    'controlleur' => 'ControlleurJoueur',
    'methode'     => 'inscription'
  ),
  'deconnexion' => array(
    'controlleur' => 'ControlleurJoueur',
    'methode'     => 'deconnexion'
  )
);

?>
