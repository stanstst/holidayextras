<?php

namespace Application\Entity\Validator;

use Application\Entity\Entity;

interface Validator
{
    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public function validate( Entity $entity );

    /**
     * @return []
     */
    public function getErrors();

}