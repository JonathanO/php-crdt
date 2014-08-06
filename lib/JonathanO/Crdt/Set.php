<?php

namespace JonathanO\Crdt;


interface Set extends Mergeable {

    /**
     * @param string $value
     */
    public function add($value);

    /**
     * @return string[]
     */
    public function getValue();

}