<?php

class EncodingRuleTest extends PHPUnit_Framework_TestCase {

  /**
   *
   * @param type $rule
   * @param type $string
   * @param type $expected 
   * 
   * @dataProvider rulesProvider
   */
  public function testApply($rule, $string, $expected) {
    $rule = new EncodingRule($rule);
    $rule->apply($string);
    $this->assertEquals($expected, $string);
  }

  public function rulesProvider() {
    return array(
      array(array(' ', '+'), 'a b c', 'a+b+c'),
      array(array('1', '3'), 'a1b1c', 'a3b3c'),
      array(array('a', 'o'), 'arasca', 'orosco'),
    );
  }

}
