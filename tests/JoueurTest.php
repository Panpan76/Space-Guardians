<?php
require_once 'includeAll.php';

use PHPUnit\Framework\TestCase;



class JoueurTest extends TestCase{

  public function testCreation(){
    $ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entités

    // Les données
    $data = array();
    $data['pseudo'] = 'Test';
    $data['mdp']    = 'Test';
    $data['race']   = $ge->select('Race', array('nom' => 'Humain'), $ge::AUCUN)->getOne()->id;


    $joueur = new Joueur();

    $joueur->pseudo           = $data['pseudo'];
    $joueur->motDePasse       = md5($data['mdp']);
    $joueur->race             = $ge->select('Race', array('id' => $data['race']), $ge::AUCUN)->getOne();

    if($ge->persist($joueur)){
      // On récupère de-nouveau notre joueur pour avoir toutes les relations qui ont été ajouté par le SGBD
      $joueur = $ge->select('Joueur', array(
        'pseudo'      => $data['pseudo']
      ), $ge::ENFANTS+$ge::PARENTS)->getOne();

    }
    $this->assertInstanceOf(get_class(new Joueur()), $joueur);
    var_dump($joueur);
  }
}
?>
