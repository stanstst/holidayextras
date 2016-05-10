<?php

namespace Application\Entity;

use Exception;

class Entity implements AttributeAggregate
{
    public function setAttributes( array $attributes )
    {
        foreach( $attributes as $attributesKey => $attributeValue )
        {
            $this->checkAttributeExists( $attributesKey );
            $this->$attributesKey = $attributeValue;
        }

    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [];
        foreach( get_object_vars( $this ) as $attributesKey => $attributeValue )
        {
            $attributes[$attributesKey] = $attributeValue;
        }

        return $attributes;
    }

    public function __get( $attribute )
    {
        if($this->$attribute instanceof \DateTime)
        {
            return $this->$attribute->format();
        }

        return $this->$attribute;
    }

    /**
     * @param $attributesKey
     *
     * @throws Exception
     */
    protected function checkAttributeExists( $attributesKey )
    {
        if( !in_array( $attributesKey, array_keys( get_object_vars( $this ) ) ) )
        {
            throw new Exception( get_class( $this ) . ' : Attribute: ' . $attributesKey . ' does not exist.' );
        }

    }
}