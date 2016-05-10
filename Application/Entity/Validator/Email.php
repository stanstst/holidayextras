<?php

namespace Application\Entity\Validator;

class Email extends ValidatorChain
{

    /**
     * @return bool
     */
    protected function assert()
    {
        return filter_var( $this->entity->{$this->validatedAttribute}, FILTER_VALIDATE_EMAIL );
    }

}