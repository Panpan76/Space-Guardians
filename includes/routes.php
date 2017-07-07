<?php

$routes = array(
  '' => array(
    'controlleur' => 'ControlleurDefaut',
    'methode'     => 'index'
  ),
  'joueur\/([^\/]+)' => array(
    'controlleur' => 'ControlleurJoueur',
    'methode'     => 'voir'
  )
);

?>
