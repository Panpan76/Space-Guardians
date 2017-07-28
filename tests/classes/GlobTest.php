<?php
require_once __DIR__.'/../includeAll.php';

use PHPUnit\Framework\TestCase;



class GlobTest extends TestCase{


  /**
   * @covers Glob::getInstance
   * @covers Glob::__construct
   * @covers Glob::__set
   */
  public function testAll(){
    $glob = Glob::getInstance();
    $glob->test = 'Test';
    $this->assertEquals('Test', $glob->test);
  }
}
?>
