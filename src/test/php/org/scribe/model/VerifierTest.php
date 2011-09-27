<?php

class VerifierTest extends PHPUnit_Framework_TestCase {
  const TEST_STRING = "verifier_string";

  public function testVerifier() {
    $v = new Verifier(self::TEST_STRING);
    $this->assertEquals(self::TEST_STRING, $v->getValue());
  }

  /**
   * @expectedException Exception
   */
  public function testVerifierE() {
    $v = new Verifier(null);
  }

}
