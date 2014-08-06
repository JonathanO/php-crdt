<?php
namespace JonathanO\Crdt\Set;


class TPSetTest extends \PHPUnit_Framework_TestCase {

    public function testAddStuff() {
        $set = new TPSet();
        $set->add("LOL");
        $set->add("LOLOLOL");
        $this->assertEquals(array("LOL", "LOLOLOL"), $set->getValue());
    }

    public function testRemoveStuff() {
        $set = new TPSet();
        $set->add("LOL");
        $set->add("LOL");
        $set->add("LOLOLOL");
        $set->add("LOLOLOLOL");
        $this->assertEquals(array("LOL", "LOLOLOL", "LOLOLOLOL"), $set->getValue());
        $set->remove("LOL");
        $this->assertEquals(array("LOLOLOL", "LOLOLOLOL"), $set->getValue());
        $set->remove("LOLOLOL");
        $this->assertEquals(array("LOLOLOLOL"), $set->getValue());
        $set->remove("LOLOLOLOL");
        $this->assertEquals(array(), $set->getValue());
    }

    public function testRemoveTombstones() {
        $set = new TPSet();
        $set->add("LOL");
        $set->add("LOLOLOL");
        $this->assertEquals(array("LOL", "LOLOLOL"), $set->getValue());
        $set->remove("LOL");
        $this->assertEquals(array("LOLOLOL"), $set->getValue());
        $set->add("LOL");
        $this->assertEquals(array("LOLOLOL"), $set->getValue());
    }

    public function testMerge() {
        $setA = new TPSet();
        $setA->add("LOL");
        $setA->add("foo");

        $setB = new TPSet();
        $setB->setA($setA->getA());
        $setB->setR($setA->getR());

        $setA->add("baz");
        $setA->remove("LOL");

        $setB->add("bar");
        $setB->remove("baz"); // Baz hasn't been seen in B yet, but it can still be removed.

        $this->assertEquals(array("foo", "baz"), $setA->getValue());
        $this->assertEquals(array("LOL", "foo", "bar"), $setB->getValue());

        $setC = $setA->merge($setB);
        $arrC = $setC->getValue();
        sort($arrC);
        $arrE = array("foo", "bar");
        sort($arrE);
        $this->assertEquals($arrE, $arrC);
    }

} 