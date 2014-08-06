<?php

namespace JonathanO\Crdt\Utils;


class UniqIdProvider implements IdProvider {

    private $myId;

    public function __construct()
    {
        $this->myId = uniqid();
    }

    public function getId()
    {
        return $this->myId;
    }
}