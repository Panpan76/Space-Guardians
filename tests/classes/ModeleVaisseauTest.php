<?php
require_once __DIR__.'/../includeAll.php';

use PHPUnit\Framework\TestCase;



class ModeleVaisseauTest extends TestCase{

  protected $ge;

  public function setUp(){
   $this->ge = GestionnaireEntite::getInstance(); // Le gestionnaire d'entites
  }

  /**
   * @covers ModeleVaisseau::postSelect
   */
  public function testCreation(){

    $modele = $this->ge->select('ModeleVaisseau', ['id' => 14], GestionnaireEntite::PARENTS)->getOne();

    $this->assertInstanceOf(get_class(new ModeleVaisseau()), $modele);
  }

}
?>
