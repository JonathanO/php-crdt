<?php
/**
 * Created by IntelliJ IDEA.
 * User: jono
 * Date: 13/07/2014
 * Time: 17:36
 */

namespace JonathanO\Crdt;


interface Mergeable {

    public function merge(Mergeable $i);

}