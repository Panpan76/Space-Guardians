<?php
require_once 'includeAll.php';

use PHPUnit\Framework\TestCase;



class GestionnaireEntiteTest extends TestCase{

  public function testAjout(){
    $ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entites

    // Les donnees
    $race = new Race();
    $race->nom = 'Race test';

    $this->assertTrue($ge->persist($race), "Un probleme est survenu lors de l'ajout de l'objet en base");
  }

  /**
   * @depends testAjout
   */
  public function testSelection(){
    $ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entites

    $race1 = new Race();
    $race1->nom = 'Race test';

    $race = $ge->select('Race', array('nom' => 'Race test'), $ge::AUCUN)->getOne();

    $this->assertEquals($race1->nom, $race->nom, "L'entite recuperee est differente de celle prevue");
  }

  /**
   * @depends testSelection
   */
  public function testModification(){
    $ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entités

    $race1 = new Race();
    $race1->nom = 'Race test2';

    $race = $ge->select('Race', array('nom' => 'Race test'), $ge::AUCUN)->getOne();
    $race->nom = 'Race test2';

    $this->assertTrue($ge->persist($race), "Un probleme est survenu lors de la modification de l'objet en base");

    $race = $ge->select('Race', array('nom' => 'Race test2'), $ge::AUCUN)->getOne();
    $this->assertEquals($race1->nom, $race->nom, "L'entite recuperee est differente de celle prevue");
  }

  /**
   * @depends testModification
   */
  public function testSuppression(){
    $ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entités

    $race = $ge->select('Race', array('nom' => 'Race test2'), $ge::AUCUN)->getOne();
    $this->assertTrue($ge->supprime($race), "Un probleme est survenu lors de la suppression de l'objet en base");

    // On essaye de récupérer l'entité précédente
    $race = $ge->select('Race', array('nom' => 'Race test2'), $ge::AUCUN)->getOne();
    $this->assertEquals(null, $race, "Quelque chose a ete recupere en base");
  }
}
?>
