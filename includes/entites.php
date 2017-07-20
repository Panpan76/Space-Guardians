<?php
/**
 * Correspondances entre entités et tables
 *
 * Le tableau doit être formaté tel que :
 * <Entite> = array(
 *    'table'     => <table>,
 *    'variables' => array(
 *      <attribut> => array(
 *        'type'    => <type>[,
 *        'colonne' => <colonne>,
 *        'entite'  => <Entite cible>,
 *        'lien'    => <attribut cible>,
 *        'relation'=> <relation>]
 *      )
 *    )
 * )
 *
 *  Valeurs possibles :
 *    type      => PK|string|int|boolean|datetime|entite
 *    relation  => 1-1|1-n|n-1|n-n
 */
$correspondances = array();
$correspondances['Joueur'] = array(
  'table'     => 'joueur',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'joueur_ID'
    ),
    'pseudo' => array(
      'type'    => 'string',
      'colonne' => 'pseudo'
    ),
    'motDePasse' => array(
      'type'    => 'string',
      'colonne' => 'mdp'
    ),
    'dateInscription' => array(
      'type'    => 'datetime',
      'colonne' => 'date_inscription'
    ),
    'race' => array(
      'type'    => 'objet',
      'entite'  => 'Race',
      'colonne' => 'race_ID',
      'relation'=> 'n-1'
    ),
    'amis' => array(
      'type'    => 'objet',
      'entite'  => 'Joueur',
      'byTable' => 'amis',
      'from'    => 'joueur_ID',
      'to'      => 'ami_ID',
      'relation'=> 'n-n'
    ),
    'planetes' => array(
      'type'    => 'objet',
      'entite'  => 'Planete',
      'lien'    => 'proprietaire',
      'relation'=> '1-n'
    ),
  )
);
$correspondances['Race'] = array(
  'table'     => 'race',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'race_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    )
  )
);
$correspondances['Galaxie'] = array(
  'table'     => 'galaxie',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'galaxie_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'largeur' => array(
      'type'    => 'int',
      'colonne' => 'largeur'
    ),
    'hauteur' => array(
      'type'    => 'int',
      'colonne' => 'hauteur'
    ),
    'systemesSolaires' => array(
      'type'    => 'objet',
      'entite'  => 'SystemeSolaire',
      'lien'    => 'galaxie',
      'relation'=> '1-n'
    )
  )
);
$correspondances['SystemeSolaire'] = array(
  'table'     => 'systeme_solaire',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'systeme_solaire_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'centreX' => array(
      'type'    => 'int',
      'colonne' => 'centre_x'
    ),
    'galaxie' => array(
      'type'      => 'objet',
      'entite'    => 'Galaxie',
      'colonne'   => 'galaxie_ID',
      'relation'  => 'n-1'
    ),
    'centreY' => array(
      'type'    => 'int',
      'colonne' => 'centre_y'
    ),
    'rayon' => array(
      'type'    => 'int',
      'colonne' => 'rayon'
    ),
    'planetes' => array(
      'type'    => 'objet',
      'entite'  => 'Planete',
      'lien'    => 'systemeSolaire',
      'relation'=> '1-n'
    )
  )
);
$correspondances['Planete'] = array(
  'table'     => 'planete',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'planete_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'systemeSolaire' => array(
      'type'    => 'objet',
      'entite'  => 'SystemeSolaire',
      'colonne' => 'systeme_solaire_ID',
      'relation'=> 'n-1'
    ),
    'x' => array(
      'type'    => 'int',
      'colonne' => 'coordonnees_x'
    ),
    'y' => array(
      'type'    => 'int',
      'colonne' => 'coordonnees_y'
    ),
    'proprietaire' => array(
      'type'      => 'objet',
      'entite'    => 'Joueur',
      'colonne'   => 'proprietaire_ID',
      'relation'  => 'n-1'
    ),
    'batiments' => array(
      'type'    => 'objet',
      'entite'  => 'Batiment',
      'byTable' => 'planete_batiment',
      'from'    => 'planete_ID',
      'to'      => 'batiment_ID',
      'relation'=> 'n-n'
    ),
    'stocks' => array(
      'type'    => 'objet',
      'entite'  => 'Ressource',
      'byTable' => 'ressource_planete',
      'from'    => 'planete_ID',
      'to'      => 'ressource_ID',
      'relation'=> 'n-n'
    ),
  )
);

$correspondances['TypeBatiment'] = array(
  'table'     => 'type_batiment',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'type_batiment_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
  )
);
$correspondances['Batiment'] = array(
  'table'     => 'batiment',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'batiment_ID'
    ),
    'typeBatiment' => array(
      'type'    => 'objet',
      'entite'  => 'TypeBatiment',
      'colonne' => 'type_batiment_ID',
      'relation'=> 'n-1'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'description' => array(
      'type'    => 'string',
      'colonne' => 'description'
    ),
    'image' => array(
      'type'    => 'string',
      'colonne' => 'image'
    ),
    'temps' => array(
      'type'    => 'int',
      'colonne' => 'temps_base'
    ),
    'ressources' => array(
      'type'    => 'objet',
      'entite'  => 'Ressource',
      'byTable' => 'ressource_batiment',
      'from'    => 'batiment_ID',
      'to'      => 'ressource_ID',
      'relation'=> 'n-n'
    ),
  )
);

$correspondances['TypeRessource'] = array(
  'table'     => 'type_ressource',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'type_ressource_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
  )
);

$correspondances['Ressource'] = array(
  'table'     => 'ressource',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'ressource_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'image' => array(
      'type'    => 'string',
      'colonne' => 'image'
    ),
    'coefficient' => array(
      'type'    => 'int',
      'colonne' => 'coefficient'
    ),
    'typeRessource' => array(
      'type'    => 'objet',
      'entite'  => 'TypeRessource',
      'colonne' => 'type_ressource_ID',
      'relation'=> 'n-1'
    ),
  )
);
?>
