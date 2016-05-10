<?php

namespace Application\Entity;

use Application\Entity\Validator\ValidatorChain;

class ValidatorFactory
{
    protected $validatorNamespace = 'Application\Entity\Validator\\';

    /**
     * @var ValidatorChain
     */
    protected $currentValidator;

    /**
     * @var ValidatorChain
     */
    protected $frontValidator;

    /**
     * @var ValidatorChain
     */
    protected $previousValidator;

    /**
     * @return static
     */
    public static function instance()
    {
        return new static();
    }

    /**
     * @param []$rules
     *
     * @return ValidatorChain
     */
    public function create( $rules )
    {

        foreach( $rules as $attribute => $validators )
        {
            $this->createAttributeValidators( $validators, $attribute );
        }

        return $this->frontValidator;
    }

    /**
     * @param $validators
     * @param $attribute
     */
    protected function createAttributeValidators( $validators, $attribute )
    {
        foreach( $validators as $validatorClass )
        {
            $validatorClassFullName = $this->validatorNamespace . $validatorClass;
            $this->currentValidator = new $validatorClassFullName( $attribute );
            $this->createChainLink();
            $this->setFrontValidator();
            $this->previousValidator = $this->currentValidator;
        }
    }

    protected function createChainLink()
    {
        if( $this->previousValidator )
        {
            $this->previousValidator->setNext( $this->currentValidator );
        }
    }

    protected function setFrontValidator()
    {
        if( !$this->frontValidator )
        {
            $this->frontValidator = $this->currentValidator;
        }
    }

}