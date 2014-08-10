<?php

namespace JonathanO\Crdt\Utils;


class UniqIdProvider implements IdProvider {

    public function getId()
    {
        return uniqid();
    }
}