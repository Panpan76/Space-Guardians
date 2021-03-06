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
    'technologies' => array(
      'type'    => 'objet',
      'entite'  => 'Technologie',
      'byTable' => 'joueur_technologie',
      'from'    => 'joueur_ID',
      'to'      => 'technologie_ID',
      'relation'=> 'n-n'
    ),
    'alliance' => array(
      'type'    => 'objet',
      'entite'  => 'Alliance',
      'colonne' => 'alliance_ID',
      'relation'=> 'n-1'
    ),
    'rang' => array(
      'type'    => 'objet',
      'entite'  => 'RangAlliance',
      'colonne' => 'rang_ID',
      'relation'=> 'n-1'
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
    'ressourcesBase' => array(
      'type'    => 'objet',
      'entite'  => 'Ressource',
      'byTable' => 'ressource_batiment',
      'from'    => 'batiment_ID',
      'to'      => 'ressource_ID',
      'relation'=> 'n-n'
    ),
  )
);

$correspondances['TypeTechnologie'] = array(
  'table'     => 'type_technologie',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'type_technologie_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
  )
);
$correspondances['Technologie'] = array(
  'table'     => 'technologie',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'technologie_ID'
    ),
    'typeTechnologie' => array(
      'type'    => 'objet',
      'entite'  => 'TypeTechnologie',
      'colonne' => 'type_technologie_ID',
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
      'byTable' => 'ressource_technologie',
      'from'    => 'technologie_ID',
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

$correspondances['RangAlliance'] = array(
  'table'     => 'alliance_rang',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'rang_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'alliance' => array(
      'type'    => 'objet',
      'entite'  => 'Alliance',
      'colonne' => 'alliance_ID',
      'relation'=> 'n-1'
    )
  )
);

$correspondances['Alliance'] = array(
  'table'     => 'alliance',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'alliance_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'tag' => array(
      'type'    => 'string',
      'colonne' => 'tag'
    ),
    'description' => array(
      'type'    => 'string',
      'colonne' => 'description'
    ),
    'rangs' => array(
      'type'    => 'objet',
      'entite'  => 'RangAlliance',
      'lien'    => 'alliance',
      'relation'=> '1-n'
    ),
    'joueurs' => array(
      'type'    => 'objet',
      'entite'  => 'Joueur',
      'lien'    => 'alliance',
      'relation'=> '1-n'
    )
  )
);

$correspondances['ClasseVaisseau'] = array(
  'table'     => 'vaisseau_classe',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'classe_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    )
  )
);

$correspondances['ModeleVaisseau'] = array(
  'table'     => 'vaisseau_modele',
  'variables' => array(
    'id' => array(
      'type'    => 'PK',
      'colonne' => 'modele_ID'
    ),
    'nom' => array(
      'type'    => 'string',
      'colonne' => 'nom'
    ),
    'temps' => array(
      'type'    => 'int',
      'colonne' => 'temps'
    ),
    'joueur' => array(
      'type'    => 'objet',
      'entite'  => 'Joueur',
      'colonne' => 'joueur_ID',
      'relation'=> 'n-1'
    ),
    'classe' => array(
      'type'    => 'objet',
      'entite'  => 'ClasseVaisseau',
      'colonne' => 'classe_ID',
      'relation'=> 'n-1'
    ),
    'ressources' => array(
      'type'    => 'objet',
      'entite'  => 'Ressource',
      'byTable' => 'vaisseau_modele_ressource',
      'from'    => 'modele_ID',
      'to'      => 'ressource_ID',
      'relation'=> 'n-n'
    ),
  )
);

$correspondances['VaisseauConstruction'] = array(
  'table'     => 'vaisseau_construction',
  'variables' => array(
    'planete' => array(
      'type'    => 'objet',
      'entite'  => 'Planete',
      'colonne' => 'planete_ID',
      'relation'=> 'n-1'
    ),
    'modele' => array(
      'type'    => 'objet',
      'entite'  => 'Modele',
      'colonne' => 'modele_ID',
      'relation'=> 'n-1'
    ),
    'quantite' => array(
      'type'    => 'int',
      'colonne' => 'quantite'
    ),
    'date' => array(
      'type'    => 'datetime',
      'colonne' => 'date_construction'
    )
  )
);
?>
