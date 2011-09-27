<?php

class MapUtilsTest extends PHPUnit_Framework_TestCase {

  private $unsorted;

  public function setUp() {
    $this->unsorted = array();
    $this->unsorted["d"] = "fourth";
    $this->unsorted["a"] = "first";
    $this->unsorted["c"] = "third";
    $this->unsorted["b"] = "second";
  }

  public function testSortMap() {
    $sorted = MapUtils::sort($this->unsorted);
    $values = array_values($sorted);
    $this->assertEquals("first", $values[0]);
    $this->assertEquals("second", $values[1]);
    $this->assertEquals("third", $values[2]);
    $this->assertEquals("fourth", $values[3]);
  }

  public function testNotModifyTheOriginalMap() {
    $sorted = MapUtils::sort($this->unsorted);
    $this->assertNotSame($sorted, $this->unsorted);

    $values = array_values($this->unsorted);
    $this->assertEquals("fourth", $values[0]);
    $this->assertEquals("first", $values[1]);
    $this->assertEquals("third", $values[2]);
    $this->assertEquals("second", $values[3]);
  }

  /**
   * @expectedException IllegalArgumentException
   * 
   */
  public function testThrowExceptionForNullMap() {
    MapUtils::sort(null);
  }

}
