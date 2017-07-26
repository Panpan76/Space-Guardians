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
    'methode'     => 'index'
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
  ),
  'planete\/(\d+)' => array(
    'controlleur' => 'ControlleurPlanete',
    'methode'     => 'voir'
  ),
  'batiment' => array(
    'controlleur' => 'ControlleurBatiment',
    'methode'     => 'index'
  ),
  'batiment\/construire\/(\d+)' => array(
    'controlleur' => 'ControlleurBatiment',
    'methode'     => 'construire'
  ),
  'technologie' => array(
    'controlleur' => 'ControlleurTechnologie',
    'methode'     => 'index'
  ),
  'technologie\/rechercher\/(\d+)' => array(
    'controlleur' => 'ControlleurTechnologie',
    'methode'     => 'rechercher'
  ),
  'alliance' => array(
    'controlleur' => 'ControlleurAlliance',
    'methode'     => 'index'
  ),
  'alliance\/nouvelle' => array(
    'controlleur' => 'ControlleurAlliance',
    'methode'     => 'nouvelle'
  ),
  'alliance\/(\d+)' => array(
    'controlleur' => 'ControlleurAlliance',
    'methode'     => 'voir'
  ),
  'modele' => array(
    'controlleur' => 'ControlleurModele',
    'methode'     => 'index'
  ),
  'modele\/nouveau' => array(
    'controlleur' => 'ControlleurModele',
    'methode'     => 'nouveau'
  ),
  'modele\/(\d+)\/construire\/(\d+)' => array(
    'controlleur' => 'ControlleurModele',
    'methode'     => 'construire'
  )
);

?>
