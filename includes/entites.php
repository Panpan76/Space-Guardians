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
    )
  )
);
?>
