<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Delivery\Web\Response as WebResponse;
use Application\Entity\User;
use Application\Entity\Validator\Validator;
use Application\Persistence\Repository;
use DateTime;
use Exception;

class Creator
{
    const USER_NOT_SAVED_ERROR = 'User not saved.';

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var DateTime
     */
    protected $currentDateTime;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Response   $response
     * @param Repository $repository
     * @param Validator  $validator
     */
    public function __construct( Response $response, Repository $repository, Validator $validator )
    {
        $this->response = $response;
        $this->repository = $repository;
        $this->currentDateTime = new DateTime();
        $this->validator = $validator;
    }

    public function create( array $userDataRequest )
    {
        $user = new User();
        $user->setAttributes( $userDataRequest );
        $user->setCreated( $this->currentDateTime );

        $this->validator->validate( $user );

        if( count( $this->validator->getErrors() ) )
        {
            $this->response->setPayload( $this->validator->getErrors() );
            $this->response->setResponseCode( Response::STATUS_BAD_REQUEST );
            return;
        }

        try
        {
            $this->repository->save( $user );
            $this->response->setPayload( $user->getAttributes() );
        }
        catch( Exception $exception )
        {
            $this->response->setPayload( self::USER_NOT_SAVED_ERROR );
            $this->response->setResponseCode( Response::STATUS_SERVER_ERROR );
        }
    }
}