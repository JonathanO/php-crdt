<?php
namespace JonathanO\Crdt\Set;


class GSetTest extends \PHPUnit_Framework_TestCase {

    public function testAddStuff() {
        $set = new GSet();
        $set->add("LOL");
        $set->add("LOLOLOL");
        $this->assertEquals(array("LOL", "LOLOLOL"), $set->getValue());
    }

    public function testAddSameThingLots() {
        $set = new GSet();
        $set->add("LOL");
        $set->add("LOL");
        $this->assertEquals(array("LOL"), $set->getValue());
    }

    public function testMerge() {
        $setA = new GSet();
        $setA->add("LOL");
        $setA->add("foo");

        $setB = new GSet();
        $setB->setE($setA->getE());

        $setA->add("baz");
        $setB->add("bar");

        $this->assertEquals(array("LOL", "foo", "baz"), $setA->getValue());
        $this->assertEquals(array("LOL", "foo", "bar"), $setB->getValue());

        $setC = $setA->merge($setB);
        $arrC = $setC->getValue();
        sort($arrC);
        $arrE = array("LOL", "foo", "bar", "baz");
        sort($arrE);
        $this->assertEquals($arrE, $arrC);
    }

} 