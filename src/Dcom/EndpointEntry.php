<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
/**
 *
 * @author thibaud
 */
class EndpointEntry
{
    private $object;

    private $towerId;

    private $tower;

    private $annotation;

    public function __construct(Uuid $object, $towerId, $annotation)
    {
        $this->object = $object;
        $this->towerId = $towerId;
        $this->annotation = $annotation;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getTowerId()
    {
        return $this->towerId;
    }

    /**
     *
     * @return Tower
     */
    public function getTower()
    {
        return $this->tower;
    }

    public function setTower(Tower $tower)
    {
        $this->tower = $tower;
    }

    public function getAnnotation()
    {
        return trim($this->annotation);
    }
}
