<?php

namespace Application\Entity\Validator;

use Application\Entity\Entity;

abstract class ValidatorChain implements Validator
{
    protected $validatedAttribute;

    protected $errors = [];

    /**
     * @var ValidatorChain
     */
    protected $next;

    /**
     * @var Entity
     */
    protected $entity;

    abstract protected function assert();

    /**
     * @param $attribute
     */
    public function __construct( $attribute )
    {
        $this->validatedAttribute = $attribute;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        if( $this->next )
        {
            $this->errors = array_merge( $this->errors, $this->next->getErrors() );
        }

        return $this->errors;
    }

    /**
     * @param Entity $entity
     *
     * @return void
     */
    public function validate( Entity $entity )
    {
        $this->entity = $entity;
        if( !$this->assert() )
        {
            $this->addError();
        }

        if( $this->next )
        {
            $this->next->validate( $this->entity );
        }
    }

    protected function addError()
    {
        $validatorName = explode( '\\', get_class( $this ) );
        $this->errors[get_class( $this->entity )][$this->validatedAttribute] = end( $validatorName );
    }

    public function setNext( ValidatorChain $next )
    {
        $this->next = $next;

        return $this->next;
    }
}