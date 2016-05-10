<?php

namespace Application\Delivery;

abstract class Request
{
    protected $identifier;

    /**
     * @return mixed
     */
    abstract public function getPayload();

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier( $identifier )
    {
        $this->identifier = $identifier;
    }
}