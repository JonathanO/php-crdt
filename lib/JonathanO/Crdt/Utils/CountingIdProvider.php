<?php

namespace JonathanO\Crdt\Utils;


class CountingIdProvider implements IdProvider {

    private static $num = 0;

    public function getId()
    {
        return self::$num++;
    }

}