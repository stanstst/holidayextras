<?php

namespace Application\Persistence;

use Application\Entity\Entity;

interface Repository
{
    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public function save( Entity $entity );

    /**
     * @param $entityName
     * @param $pk
     *
     * @return mixed
     */
    public function findByPk( $entityName, $pk );

    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public function delete( $entity );
}