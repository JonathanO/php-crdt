<?php

namespace JonathanO\Crdt\Counter;

use JonathanO\Crdt\Counter;
use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Utils\IdProvider;
use Weasel\JsonMarshaller\Config\DoctrineAnnotations\JsonProperty;
use Weasel\JsonMarshaller\Config\DoctrineAnnotations\JsonTypeName;


/**
 * Class GCounter
 * @package JonathanO\Crdt\Counter
 * @JsonTypeName("g-counter")
 */
class GCounter implements Counter {

    /**
     * @var IdProvider
     */
    public static $defaultIdProvider;

    /**
     * @var IdProvider
     */
    private $idProvider;

    /**
     * @var int[]
     */
    private $e = array();

    /**
     * @param IdProvider $idProvider
     */
    public function setIdProvider($idProvider)
    {
        $this->idProvider = $idProvider;
    }

    public function __construct($idProvider = null) {
        if (isset($idProvider)) {
            $this->idProvider = $idProvider;
        } else {
            $this->idProvider = self::$defaultIdProvider;
        }
    }

    public function increment($step = 1)
    {
        $id = $this->idProvider->getId();
        if (!isset($this->e[$id])) {
            $this->e[$id] = 0;
        }
        $this->e[$id] += $step;
    }

    public function getValue()
    {
        $sum = 0;
        foreach ($this->e as $step) {
            $sum += $step;
        }
        return $sum;
    }

    /**
     * @return int[]
     * @JsonProperty(type="int[string]")
     */
    public function getE() {
        return $this->e;
    }

    /**
     * @param $e int[]
     * @JsonProperty(type="int[string]")
     */
    public function setE($e) {
        $this->e = $e;
    }

    public function merge(Mergeable $c) {
        if (!$c instanceof GCounter) {
            throw new \InvalidArgumentException("Cannot merge a counter of a different type");
        }
        $result = new GCounter();
        foreach($this->e as $id => $count) {
            if (isset($c->e[$id])) {
                $result->e[$id] = max($count, $c->e[$id]);
            } else {
                $result->e[$id] = $count;
            }
        }
        $result->e += $c->e; // Merge in the elements that are in $c but not $this.
        return $result;
    }
}