<?php

namespace JonathanO\Crdt\Set;

use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Set;
use Weasel\JsonMarshaller\Config\Annotations\JsonProperty;
use Weasel\JsonMarshaller\Config\Annotations\JsonTypeName;

/**
 * Class TPSet
 * @package JonathanO\Crdt\Set
 * @JsonTypeName("2p-set")
 */
class TPSet implements Set {

    /**
     * @var GSet
     */
    private $a;

    /**
     * @var GSet
     */
    private $r;

    public function __construct()
    {
        $this->a = new GSet();
        $this->r = new GSet();
    }

    /**
     * @param string $value
     */
    public function add($value)
    {
        $this->a->add($value);
    }

    public function remove($value)
    {
        $this->r->add($value);
    }

    /**
     * @return string[]
     */
    public function getValue()
    {
        return array_values(array_diff($this->a->getValue(), $this->r->getValue()));
    }

    /**
     * @return array
     * @JsonProperty(type="string[]")
     */
    public function getA()
    {
        return $this->a->getE();
    }

    /**
     * @param array $a
     * @JsonProperty(type="string[]")
     */
    public function setA(array $a)
    {
        $this->a->setE($a);
    }

    /**
     * @return array
     * @JsonProperty(type="string[]")
     */
    public function getR()
    {
        return $this->r->getE();
    }

    /**
     * @param array $r
     * @JsonProperty(type="string[]")
     */
    public function setR(array $r)
    {
        $this->r->setE($r);
    }

    public function merge(Mergeable $s)
    {
        if (!$s instanceof TPSet) {
            throw new \InvalidArgumentException("Cannot merge a set of a different type");
        }
        $result = new TPSet();
        $result->a = $this->a->merge($s->a);
        $result->r = $this->r->merge($s->r);
        return $result;
    }
}