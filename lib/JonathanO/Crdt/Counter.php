<?php

namespace JonathanO\Crdt;

interface Counter extends Mergeable {

    public function increment($step = 1);

    public function getValue();

}