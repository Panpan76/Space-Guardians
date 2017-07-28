<?php
require_once __DIR__.'/../includeAll.php';

use PHPUnit\Framework\TestCase;



class GestionnaireEntiteTest extends TestCase{

  protected $ge;

  public function setUp(){
   $this->ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entites
  }

  public function insertProvider(){
    // Les donnees

    // Une alliance
    $alliance = new Alliance();
    $alliance->nom          = 'Alliance Test';
    $alliance->tag          = 'TEST';
    $alliance->description  = 'Description Test';

    // Un type de ressource
    $typeRessource = new TypeRessource();
    $typeRessource->nom = 'Type Ressource Test';

    // Une ressource
    $ressource = new Ressource();
    $ressource->nom = 'Ressource Test';
    $ressource->coefficient = 0.5;
    $ressource->typeRessource = $typeRessource;
    $ressource->quantite = 42;

    // Une technologie
    $technologie = new Technologie();
    $technologie->nom               = 'Technologie Test';
    $technologie->typeTechnologie   = 1;
    $technologie->description       = 'Description test';
    $technologie->image             = null;
    $technologie->temps             = 7;
    $technologie->ressources        = array(
      $ressource
    );

    // Une race
    $race = new Race();
    $race->nom = 'Race Test';

    return [
      [$alliance],
      [$typeRessource],
      [$ressource],
      [$technologie],
      [$race],
    ];
  }

  public function selectProvider(){
    return [
      ['Alliance', ['nom' => 'Alliance Test'], GestionnaireEntite::ENFANTS+GestionnaireEntite::PARENTS+GestionnaireEntite::BEAUXFRERES],
      ['Ressource', ['nom' => 'Ressource Test'], GestionnaireEntite::AUCUN],
      ['Ressource', ['nom' => 'Ressource Test'], GestionnaireEntite::PARENTS], // Pour tester la surcharge de mapping
      ['Technologie', ['nom' => 'Technologie test'], GestionnaireEntite::PARENTS+GestionnaireEntite::ENFANTS+GestionnaireEntite::FRERES],
      ['Race', ['nom' => 'Race Test'], GestionnaireEntite::AUCUN],
      ['Joueur', ['alliance' => null], GestionnaireEntite::AUCUN],
    ];
  }

  public function selectFailProvider(){
    return [
      ['Ressource', ['nom' => 'AUCUNE RESSOURCE'], GestionnaireEntite::AUCUN],
      ['ENTITETEST', [], GestionnaireEntite::ENFANTS],
    ];
  }

  public function selectAllProvider(){
    return [
      ['Ressource', [], GestionnaireEntite::AUCUN],
      ['Ressource', [], GestionnaireEntite::PARENTS], // Pour tester la surcharge de mapping
      ['Technologie', [], GestionnaireEntite::PARENTS+GestionnaireEntite::ENFANTS],
      ['Race', [], GestionnaireEntite::AUCUN],
      ['Joueur', [], GestionnaireEntite::AUCUN],
      ['Batiment', [], GestionnaireEntite::ENFANTS],
      ['Batiment', [], GestionnaireEntite::PARENTS+GestionnaireEntite::ENFANTS],
    ];
  }

  public function updateProvider(){
    return [
      ['Alliance', ['nom' => 'Alliance Test'], GestionnaireEntite::AUCUN],
      ['Ressource', ['nom' => 'Ressource Test'], GestionnaireEntite::AUCUN],
      ['Technologie', ['nom' => 'Technologie test'], GestionnaireEntite::PARENTS+GestionnaireEntite::ENFANTS],
      ['Race', ['nom' => 'Race Test'], GestionnaireEntite::AUCUN],
    ];
  }

  public function supprimeProvider(){
    return [
      ['Alliance', ['nom' => 'Alliance Test 2'], GestionnaireEntite::AUCUN],
      ['Ressource', ['nom' => 'Ressource Test 2'], GestionnaireEntite::AUCUN],
      ['Technologie', ['nom' => 'Technologie test 2'], GestionnaireEntite::PARENTS+GestionnaireEntite::ENFANTS],
      ['Race', ['nom' => 'Race Test 2'], GestionnaireEntite::AUCUN],
    ];
  }


  /**
   * @covers GestionnaireEntite::getInstance
   * @covers GestionnaireEntite::__construct
   * @covers GestionnaireEntite::setCorrespondances
   */
  public function testInit(){
    $this->assertNotNull($this->ge);
  }

  /**
   * @dataProvider insertProvider
   * @covers GestionnaireEntite::persist
   * @covers GestionnaireEntite::persistManyToMany
   * @covers GestionnaireEntite::getCorrespondances
   */
  public function testAjout($entite){
    $this->assertTrue($this->ge->persist($entite), "Un probleme est survenu lors de l'ajout de l'objet en base");
  }


  /**
   * @depends testAjout
   * @dataProvider selectProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::getOne
   */
  public function testSelection($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    $this->assertInstanceOf($classe, $entite, "L'entite recuperee n'est pas ce qu'elle devrait etre");
  }

  /**
   * @dataProvider selectFailProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntite::getCorrespondances
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::getOne
   */
  public function testSelectionFail($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    $this->assertNull($entite, "Le resultat devrait etre null");
  }

  /**
   * @dataProvider selectAllProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::aleatoire
   * @covers GestionnaireEntiteResultats::getAll
   */
  public function testSelectionAll($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getAll();
    $this->assertContainsOnlyInstancesOf($classe, $entite, "Les entites recuperees ne sont pas ce qu'elles devraient etre");
    $entite = $this->ge->select($classe, $where, $mapping)->aleatoire()->getAll();
    $this->assertContainsOnlyInstancesOf($classe, $entite, "Les entites recuperees ne sont pas ce qu'elles devraient etre apres avoir ete melanges");
  }

  /**
   * @depends testAjout
   * @dataProvider updateProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::getOne
   */
  public function testUpdate($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    foreach($where as $attribut => $valeur){
      $entite->$attribut = "$valeur 2";
    }
    $this->assertTrue($this->ge->persist($entite), "Un probleme est survenu lors de la mise a jour de l'objet en base");
  }

  /**
   * @depends testUpdate
   * @dataProvider supprimeProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntite::supprime
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::getOne
   */
  public function testSuppression($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    $this->assertTrue($this->ge->supprime($entite), "Un probleme est survenu lors de la suppression");

    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    $this->assertNull($entite, "Aucune entite ne devrait etre recuperee");
  }

  /**
   * @depends testUpdate
   * @dataProvider supprimeProvider
   * @covers GestionnaireEntite::select
   * @covers GestionnaireEntite::existe
   * @covers GestionnaireEntite::charger
   * @covers GestionnaireEntite::creerRequete
   * @covers GestionnaireEntite::ManyToMany
   * @covers GestionnaireEntite::supprime
   * @covers GestionnaireEntiteResultats::__construct
   * @covers GestionnaireEntiteResultats::getOne
   */
  public function testSuppressionFail($classe, $where, $mapping){
    $entite = $this->ge->select($classe, $where, $mapping)->getOne();
    $this->assertFalse($this->ge->supprime($entite), "Un probleme est survenu lors de la suppression");
  }

  /**
   * @depends testSuppression
   * @covers GestionnaireEntite::getRequetes
   * @covers GestionnaireEntite::getNbRequetes
   */
  public function testGetRequetes(){
    $this->assertInternalType('array', $this->ge->getRequetes(), "Le retour n'est pas un tableau");
    $this->assertInternalType('int', $this->ge->getNbRequetes(), "Le retour n'est pas un entier");
  }

  /**
   * @depends testInit
   * @covers GestionnaireEntite::setCorrespondances
   */
  public function testCorrespondancesFail(){
    $this->assertFalse($this->ge->setCorrespondances('test.php'));
  }
  
}
?>
