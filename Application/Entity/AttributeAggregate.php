<?php

namespace Application\Entity;

interface AttributeAggregate
{
    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function setAttributes(array $attributes);

    /**
     * @return array
     */
    public function getAttributes();
}