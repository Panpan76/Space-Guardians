<?php
require_once __DIR__.'/../includeAll.php';

use PHPUnit\Framework\TestCase;



class RouteurTest extends TestCase{

  /**
   * @covers Routeur::getInstance
   * @covers Routeur::__construct
   * @covers Routeur::setRoutes
   */
  public function testInit(){
    $routeur = Routeur::getInstance();

    $this->assertInstanceOf(get_class(Routeur::getInstance()), $routeur, "Un probleme est survenu lors de l'instanciation du routeur");
  }

  public function routesProvider(){
    return [
      ['login', ['pseudo' => 'Test', 'mdp' => 'Test']],
      ['alliance/1', []],
      ['test', []],
    ];
  }

  /**
   * @depends testInit
   * @dataProvider routesProvider
   * @covers Routeur::charge
   * @covers Routeur::getPage
   * @covers Routeur::cleanRoute
   */
  public function testChargement($url, $data){
    $routeur = Routeur::getInstance();

    $this->setOutputCallback(function() {});
    $this->assertNull($routeur->charge($url, $data));
  }

  /**
   * @depends testInit
   * @covers Routeur::setRoutes
   */
  public function testRoutesFailed(){
    $routeur = Routeur::getInstance();

    $this->assertFalse($routeur->setRoutes('test.php'));
  }

}
?>
