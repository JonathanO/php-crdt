<?php
namespace JonathanO\Crdt\Counter;

use Mockery as m;
use JonathanO\Crdt\Utils\CountingIdProvider;

class GCounterTest extends \PHPUnit_Framework_TestCase {

    public function testIncrements() {
        $idProvider = new CountingIdProvider();
        $counter = new GCounter($idProvider);
        $this->assertEquals(0, $counter->getValue());
        $counter->increment();
        $this->assertEquals(1, $counter->getValue());
        $counter->increment(2);
        $this->assertEquals(3, $counter->getValue());
    }

    public function testMerge() {
        $counterA = new GCounter(new CountingIdProvider());
        $counterA->increment();
        $counterA->increment();
        $counterA->increment();

        $counterB = new GCounter(new CountingIdProvider());
        $counterB->setE($counterA->getE());
        $this->assertEquals(3, $counterA->getValue());
        $this->assertEquals(3, $counterB->getValue());
        $counterB->increment();
        $counterA->increment();
        $this->assertEquals(4, $counterA->getValue());
        $this->assertEquals(4, $counterB->getValue());

        $counterC = $counterA->merge($counterB);
        $this->assertEquals(5, $counterC->getValue());
    }

    public function testMidMerge() {
        $counterA = new GCounter(new CountingIdProvider());
        $counterA->setE(array("a" => 1, "c" => 1, "e" => 1));

        $counterB = new GCounter(new CountingIdProvider());
        $counterB->setE(array("a" => 1, "b" => 1, "c" => 1));
        $this->assertEquals(3, $counterA->getValue());
        $this->assertEquals(3, $counterB->getValue());
        $counterC = $counterA->merge($counterB);
        $this->assertEquals(4, $counterC->getValue());
    }

} 