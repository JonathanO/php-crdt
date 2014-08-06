<?php

namespace JonathanO\Crdt\Utils;


class CountingIdProvider implements IdProvider {

    private static $num = 0;

    private $myId;

    public function __construct()
    {
        $this->myId = self::$num++;
    }

    public function getId()
    {
        return $this->myId;
    }

}