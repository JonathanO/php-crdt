<?php

namespace JonathanO\Crdt\Counter;

use JonathanO\Crdt\Counter;
use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Utils\IdProvider;
use Weasel\JsonMarshaller\Config\DoctrineAnnotations\JsonProperty;
use Weasel\JsonMarshaller\Config\DoctrineAnnotations\JsonTypeName;


/**
 * Class PNCounter
 * @package JonathanO\Crdt\Counter
 * @JsonTypeName("pn-counter")
 */
class PNCounter implements Counter {

    /**
     * @var IdProvider
     */
    public static $defaultIdProvider;

    /**
     * @var IdProvider
     */
    private $idProvider;

    /**
     * @var GCounter
     */
    private $p;

    /**
     * @var GCounter
     */
    private $n;

    /**
     * @param IdProvider $idProvider
     */
    public function setIdProvider($idProvider)
    {
        $this->idProvider = $idProvider;
        $this->n->setIdProvider($idProvider);
        $this->p->setIdProvider($idProvider);
    }

    public function __construct($idProvider = null) {
        if (isset($idProvider)) {
            $this->idProvider = $idProvider;
        } else {
            $this->idProvider = self::$defaultIdProvider;
        }
        $this->p = new GCounter($this->idProvider);
        $this->n = new GCounter($this->idProvider);
    }

    public function increment($step = 1)
    {
        $this->p->increment($step);
    }

    public function decrement($step = 1)
    {
        $this->n->increment($step);
    }

    public function getValue()
    {
        return $this->p->getValue() - $this->n->getValue();
    }

    /**
     * @return int[]
     * @JsonProperty(type="int[string]")
     */
    public function getP() {
        return $this->p->getE();
    }

    /**
     * @param $p int[]
     * @JsonProperty(type="int[string]")
     */
    public function setP($p) {
        $this->p->setE($p);
    }

    /**
     * @return int[]
     * @JsonProperty(type="int[string]")
     */
    public function getN() {
        return $this->n->getE();
    }

    /**
     * @param $n int[]
     * @JsonProperty(type="int[string]")
     */
    public function setN($n) {
        $this->n->setE($n);
    }

    public function merge(Mergeable $c) {
        if (!$c instanceof PNCounter) {
            throw new \InvalidArgumentException("Cannot merge a counter of a different type");
        }
        $result = new PNCounter($this->idProvider);
        $result->p = $this->p->merge($c->p);
        $result->n = $this->n->merge($c->n);
        return $result;
    }
}