<?php

namespace JonathanO\Crdt\Utils;

class MicrotimeTimeSource implements TimeSource {

    public function get()
    {
        return microtime(true);
    }

    public function compare($a, $b)
    {
        return ($a == $b ? 0 : ($a > $b ? 1 : -1));
    }
}