<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Persistence\Repository;

class Finder
{
    const ENTITY_CLASS = 'User';

    const RECORD_NOT_FOUND = 'Record not Found.';

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Finder constructor.
     *
     * @param Response   $response
     * @param Repository $repository
     */
    public function __construct( $response, $repository )
    {
        $this->response = $response;
        $this->repository = $repository;
    }

    public function find( $id )
    {
        /** @var User $userEntity */
        $userEntity = $this->repository->findByPk( self::ENTITY_CLASS, $id );

        if(!$userEntity)
        {
            $this->response->setPayload( self::RECORD_NOT_FOUND );
            $this->response->setResponseCode( Response::STATUS_RESOURCE_NOT_FOUND );
            return;
        }

        $this->response->setPayload($userEntity->getAttributes());

    }

}