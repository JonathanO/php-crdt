<?php

namespace JonathanO\Crdt\Utils;


interface IdProvider {

    /**
     * @return string Unique ID to be used to identify the "node"
     */
    public function getId();

} 