<?php
namespace JonathanO\Crdt\Counter;

use Mockery as m;
use JonathanO\Crdt\Utils\CountingIdProvider;

class PNCounterTest extends \PHPUnit_Framework_TestCase
{

    public function testIncrements()
    {
        $idProvider = new CountingIdProvider();
        $counter = new PNCounter($idProvider);
        $this->assertEquals(0, $counter->getValue());
        $counter->increment();
        $this->assertEquals(1, $counter->getValue());
        $counter->increment(2);
        $this->assertEquals(3, $counter->getValue());
    }

    public function testDecrements()
    {
        $idProvider = new CountingIdProvider();
        $counter = new PNCounter($idProvider);
        $this->assertEquals(0, $counter->getValue());
        $counter->decrement();
        $this->assertEquals(-1, $counter->getValue());
        $counter->decrement(2);
        $this->assertEquals(-3, $counter->getValue());
    }

    public function testIncDec()
    {
        $idProvider = new CountingIdProvider();
        $counter = new PNCounter($idProvider);
        $this->assertEquals(0, $counter->getValue());
        $counter->increment();
        $this->assertEquals(1, $counter->getValue());
        $counter->decrement();
        $this->assertEquals(0, $counter->getValue());
        $counter->increment(2);
        $this->assertEquals(2, $counter->getValue());
    }

    public function testMerge() {
        $counterA = new PNCounter(new CountingIdProvider());
        $counterA->increment();
        $counterA->increment();
        $counterA->decrement();
        $counterA->increment();

        $counterB = new PNCounter(new CountingIdProvider());
        $counterB->setP($counterA->getP());
        $counterB->setN($counterA->getN());
        $this->assertEquals(2, $counterA->getValue());
        $this->assertEquals(2, $counterB->getValue());
        $counterB->increment();
        $counterA->increment();
        $this->assertEquals(3, $counterA->getValue());
        $this->assertEquals(3, $counterB->getValue());
        $counterB->decrement();
        $counterB->decrement();
        $this->assertEquals(1, $counterB->getValue());

        $counterC = $counterA->merge($counterB);
        $this->assertEquals(2, $counterC->getValue());
    }

} 