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
?>
