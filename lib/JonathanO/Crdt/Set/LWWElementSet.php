<?php

namespace JonathanO\Crdt\Set;

use JonathanO\Crdt\Counter\PNCounter;
use JonathanO\Crdt\Mergeable;
use JonathanO\Crdt\Set;
use JonathanO\Crdt\Utils\TimeSource;

class LWWElementSet implements Set {

    const BIAS_ADD = "a";
    const BIAS_REMOVE = "r";

    /**
     * @var TimeSource
     */
    public static $defaultTimeSource;

    public static $defaultBias = self::BIAS_ADD;

    private $a;

    private $r;

    private $bias;

    /**
     * @var TimeSource
     */
    private $timeSource;

    /**
     * @param null $bias
     */
    public function setBias($bias)
    {
        $this->bias = $bias;
    }

    /**
     * @param null $timeSource
     */
    public function setTimeSource($timeSource)
    {
        $this->timeSource = $timeSource;
    }

    public function __construct($timeSource = null, $bias = null)
    {
        if (!isset($timeSource)) {
            $timeSource = self::$defaultTimeSource;
        }
        $this->timeSource = $timeSource;
        if (!isset($bias)) {
            $bias = self::$defaultBias;
        }
        $this->bias = $bias;

        $keyFunction = function($value) {
            return $value[0];
        };

        $mergeFunction = function($a, $b) use ($timeSource) {
            return ($timeSource->compare($a, $b) >= 0 ? $a : $b);
        };
        $this->a = new BaseSet($keyFunction, $mergeFunction);
        $this->r = new BaseSet($keyFunction, $mergeFunction);
    }

    /**
     * @param string $value
     */
    public function add($value)
    {
        $this->a->add($value, $this->timeSource->get());
    }

    public function remove($value)
    {
        $this->r->add($value, $this->timeSource->get());
    }

    /**
     * @return string[]
     */
    public function getValue()
    {
        $result = array();
        foreach ($this->a->getValue() as $value => $addTs) {
            $removedTs = $this->r->get($value);
            if (isset($removed)) {
                if ($addTs > $removedTs || ($addTs ==  $removedTs && $this->bias == self::BIAS_ADD)) {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    public function merge(Mergeable $s)
    {
        if (!$s instanceof LWWElementSet) {
            throw new \InvalidArgumentException("Cannot merge a set of a different type");
        }
        $result = new LWWElementSet($this->timeSource, $this->bias);
        $result->a = $this->a->merge($s->a);
        $result->r = $this->r->merge($s->r);
        return $result;
    }
}