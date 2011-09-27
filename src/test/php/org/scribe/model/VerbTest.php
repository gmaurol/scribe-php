<?php

class VerbTest extends PHPUnit_Framework_TestCase {

  /**
   * @param type $verb 
   * @dataProvider constantsProvider
   */
  public function testConstants($verb, $constant) {
    $this->assertEquals($verb, $constant);
  }

  public function constantsProvider() {
    return array(
      array('GET', Verb::GET),
      array('POST', Verb::POST),
      array('PUT', Verb::PUT),
      array('DELETE', Verb::DELETE)
    );
  }

}
