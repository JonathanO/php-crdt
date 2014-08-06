<?php

namespace JonathanO\Crdt\Utils;


interface TimeSource {

    /**
     * @return mixed a monotonically increasing time that is expected to be synchronised over all clients (or at least
     * usable to provide ordering across all nodes.) The better this clock is, the less likely oddness will occur
     * with LWW based stuff.
     */
    public function get();

    /**
     * @param $a
     * @param $b
     * @return int 1 if $a > $b, -1 if $a < $b, 0 if $a == $b
     */
    public function compare($a, $b);

} 