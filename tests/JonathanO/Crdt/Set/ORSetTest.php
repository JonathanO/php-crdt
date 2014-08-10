<?php
namespace JonathanO\Crdt\Set;


use JonathanO\Crdt\Utils\CountingIdProvider;

class ORSetTest extends \PHPUnit_Framework_TestCase {

    public function testAddStuff() {
        $set = new ORSet(new CountingIdProvider());
        $set->add("LOL");
        $set->add("LOLOLOL");
        $this->assertEquals(array("LOL", "LOLOLOL"), $set->getValue());
    }

    public function testRemoveStuff() {
        $set = new ORSet(new CountingIdProvider());
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

} 