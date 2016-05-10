<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Persistence\Repository;

class Deleter
{
    const ENTITY_CLASS = 'User';

    const RECORD_NOT_FOUND = 'Record not found.';

    const RECORD_NOT_DELETED = 'Record not deleted.';

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Deleter constructor.
     *
     * @param Response   $response
     * @param Repository $repository
     */
    public function __construct( $response, $repository )
    {
        $this->response = $response;
        $this->repository = $repository;
    }

    /**
     * @param $id
     */
    public function delete( $id )
    {
        /** @var User $userEntity */
        $userEntity = $this->repository->findByPk( self::ENTITY_CLASS, $id );

        if( !$userEntity )
        {
            $this->response->setPayload( self::RECORD_NOT_FOUND );
            $this->response->setResponseCode( Response::STATUS_RESOURCE_NOT_FOUND );
            return;
        }

        try
        {

            $this->repository->delete( $userEntity );
            $this->response->setResponseCode( Response::STATUS_NO_CONTENT );
        }
        catch( \Exception $exception )
        {
            $this->response->setPayload( self::RECORD_NOT_DELETED );
            $this->response->setResponseCode( Response::STATUS_SERVER_ERROR );

        }

    }
}