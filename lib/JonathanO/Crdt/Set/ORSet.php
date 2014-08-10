<?php

namespace JonathanO\Crdt\Set;

use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Set;
use JonathanO\Crdt\Utils\IdProvider;

class ORSet implements Set {

    /**
     * @var IdProvider
     */
    public static $defaultIdProvider;

    private $a;

    private $r;

    /**
     * @var IdProvider
     */
    private $idProvider;

    /**
     * @param null $idProvider
     */
    public function setIdProvider($idProvider)
    {
        $this->idProvider = $idProvider;
    }

    public function __construct($idProvider = null)
    {
        if (!isset($idProvider)) {
            $idProvider = self::$defaultIdProvider;
        }
        $this->idProvider = $idProvider;

        $mergeFunction = function(Mergeable $a, Mergeable $b) {
            return $a->merge($b);
        };
        $this->a = new BaseSet($mergeFunction);
        $this->r = new BaseSet($mergeFunction);
    }

    /**
     * @param string $value
     */
    public function add($value)
    {
        $cur = new GSet();
        $cur->add($this->idProvider->getId());
        $this->a->add($value, $cur);
    }

    public function remove($value)
    {
        $addSet = $this->a->get($value);
        if (isset($addSet)) {
            $this->r->add($value, $addSet);
        }
    }

    /**
     * @return string[]
     */
    public function getValue()
    {
        $result = array();
        foreach ($this->a->getValue() as $key => $addIdSet) {
            $removedIdSet = $this->r->get($key);
            if ($removedIdSet == null || count(array_diff($addIdSet->getValue(), $removedIdSet->getValue())) > 0) {
                $result[] = $key;
            }
        }
        return $result;
    }

    public function merge(Mergeable $s)
    {
        if (!$s instanceof ORSet) {
            throw new \InvalidArgumentException("Cannot merge a set of a different type");
        }
        $result = new ORSet($this->idProvider);
        $result->a = $this->a->merge($s->a);
        $result->r = $this->r->merge($s->r);
        return $result;
    }
}