<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Entity\Validator\Validator;
use Application\Persistence\Repository;

class Updater
{
    const USER_ENTITY = 'User';

    const RECORD_NOT_FOUND = 'Record not Found.';

    const RECORD_NOT_SAVED = 'Record Not Saved.';

    /**
     * @var User
     */
    protected $existingUser;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var []
     */
    protected $requestData;

    protected $identifier;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Updater constructor.
     *
     * @param Response   $response
     * @param Repository $repository
     * @param Validator  $validator
     */
    public function __construct( Response $response, Repository $repository, Validator $validator )
    {
        $this->response = $response;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param $identifier
     * @param $requestData
     */
    public function update($identifier,  $requestData )
    {
        $this->requestData = $requestData;
        $this->identifier = $identifier;

        $this->existingUser = $this->repository->findByPk( self::USER_ENTITY, $this->identifier );
        if( !$this->existingUser )
        {
            $this->response->setPayload( self::RECORD_NOT_FOUND );
            $this->response->setResponseCode( Response::STATUS_RESOURCE_NOT_FOUND );
            return;
        }

        try
        {
            $this->persistUserWithNewAttributes();
        }
        catch( \Exception $exception )
        {
            $this->response->setPayload( self::RECORD_NOT_SAVED );
            $this->response->setResponseCode( Response::STATUS_SERVER_ERROR );
            return;
        }
    }

    protected function persistUserWithNewAttributes()
    {
        $this->existingUser->setAttributes( $this->requestData );
        $this->validator->validate( $this->existingUser );
        if( count( $this->validator->getErrors() ) )
        {
            $this->response->setPayload( $this->validator->getErrors() );
            $this->response->setResponseCode( Response::STATUS_BAD_REQUEST );
            return;
        }
        $this->repository->save( $this->existingUser );
        $this->addNewUserToResponse();
    }

    protected function addNewUserToResponse()
    {
        /** @var User $updatedUser */
        $updatedUser = $this->repository->findByPk( self::USER_ENTITY, $this->identifier );
        $this->response->setPayload( $updatedUser->getAttributes() );
    }

}