<?php

namespace JonathanO\Crdt\Set;

use JonathanO\Crdt\Set;

/**
 * Class BaseSet
 * @package JonathanO\Crdt\Set
 */
class BaseSet {

    /**
     * @var mixed[]
     */
    private $e = array();

    /**
     * @var callable
     */
    private $mergeFunction;

    public function __construct($mergeFunction)
    {
        $this->mergeFunction = $mergeFunction;
    }

    public function add($key, $value)
    {
        if (isset($this->e[$key])) {
            $f = $this->mergeFunction;
            $this->e[$key] = $f($this->e[$key], $value);
        } else {
            $this->e[$key] = $value;
        }
    }

    public function get($key)
    {
        return isset($this->e[$key]) ? $this->e[$key] : null;
    }

    public function getValue()
    {
        return $this->e;
    }

    public function merge(BaseSet $s)
    {
        $result = new BaseSet($this->mergeFunction);
        foreach ($this->e as $key => $value) {
            $result->add($key, $value);
        }
        foreach ($s->e as $key => $value) {
            $result->add($key, $value);
        }
        return $result;
    }
}