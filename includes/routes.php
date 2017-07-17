<?php

$routes = array(
  '' => array(
    'controlleur' => 'ControlleurDefaut',
    'methode'     => 'index'
  ),
  'joueur\/(\d+)' => array(
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
  ),
  'galaxie\/(\d+)' => array(
    'controlleur' => 'ControlleurGalaxie',
    'methode'     => 'voir'
  ),
  'galaxie\/nouvelle' => array(
    'controlleur' => 'ControlleurGalaxie',
    'methode'     => 'nouvelle'
  ),
  'systemeSolaire\/json\/(\d+)' => array(
    'controlleur' => 'ControlleurSystemeSolaire',
    'methode'     => 'getJSON'
  ),
  'systemeSolaire\/(\d+)' => array(
    'controlleur' => 'ControlleurSystemeSolaire',
    'methode'     => 'voir'
  )
);

?>
