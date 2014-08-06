<?php

namespace JonathanO\Crdt\Set;

use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Set;
use Weasel\JsonMarshaller\Config\Annotations\JsonProperty;
use Weasel\JsonMarshaller\Config\Annotations\JsonTypeName;

/**
 * Class GSet
 * @package JonathanO\Crdt\Set
 * @JsonTypeName("g-set")
 */
class GSet implements Set {

    /**
     * @var BaseSet
     */
    private $e;

    public function __construct()
    {
        $this->e = new BaseSet(function($a, $b) { return $a; });
    }

    /**
     * @param string $value
     */
    public function add($value)
    {
        $this->e->add($value, $value);
    }

    /**
     * @return string[]
     */
    public function getValue()
    {
        return array_keys($this->e->getValue());
    }

    /**
     * @return array
     * @JsonProperty(type="string[]")
     */
    public function getE()
    {
        return $this->getValue();
    }

    /**
     * @param array $e
     * @JsonProperty(type="string[]")
     */
    public function setE(array $e)
    {
        foreach ($e as $value) {
            $this->e->add($value, $value);
        }
    }

    public function merge(Mergeable $s)
    {
        if (!$s instanceof GSet) {
            throw new \InvalidArgumentException("Cannot merge a set of a different type");
        }
        $result = new GSet();
        $result->e = $this->e->merge($s->e);
        return $result;
    }
}