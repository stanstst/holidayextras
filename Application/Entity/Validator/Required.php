<?php

namespace Application\Entity\Validator;


class Required extends ValidatorChain
{

    /**
     * @return bool
     */
    protected function assert()
    {
        return (bool)$this->entity->{$this->validatedAttribute} ;
    }

}