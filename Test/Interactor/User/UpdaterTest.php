<?php

namespace Test\Inreractor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Entity\Validator\Validator;
use Application\Interactor\User\Updater;
use Application\Persistence\Repository;
use Exception;
use \PHPUnit_Framework_TestCase;

class UpdaterTest extends PHPUnit_Framework_TestCase
{
    protected $userId = 123;

    protected $requestData = [
        'email'    => 'jim@mail.com',
        'forename' => 'jim',
        'surname'  => 'doeson',
        'created'  => null,
    ];

    protected $userCurrentAttributes = [
        'email'    => 'john@mail.com',
        'forename' => 'john',
        'surname'  => 'doe',
        'created'  => null,
    ];

    /**
     * @var Updater
     */
    protected $object;

    /**
     * @var Response | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $responseMock;

    /**
     * @var Repository | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repositoryMock;

    /**
     * @var Validator | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $validatorMock;

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder( Response::class )
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->repositoryMock = $this->getMockBuilder( Repository::class )
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->validatorMock = $this->getMockBuilder( Validator::class )
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->object = new Updater( $this->responseMock, $this->repositoryMock, $this->validatorMock );
        parent::setUp();
    }

    /**
     * @test
     */
    public function callSaveOnRepositoryWithUpdatedUserEntity()
    {

        /** @var User $existingEntity */
        $existingEntity = $this->getUserEntity( $this->userCurrentAttributes );

        /** @var User $updatedEntity */
        $updatedEntity = $this->getUserEntity( $this->requestData );

        $this->validatorMock->expects( $this->once() )
                            ->method( 'getErrors' )
                            ->willReturn( [] );

        $this->repositoryMock->expects( $this->at( 0 ) )
                             ->method( 'findByPk' )
                             ->with( 'User', $this->userId )
                             ->willReturn( $existingEntity );

        $this->repositoryMock->expects( $this->at( 1 ) )
                             ->method( 'save' )
                             ->with( $updatedEntity );

        $this->repositoryMock->expects( $this->at( 2 ) )
                             ->method( 'findByPk' )
                             ->with( 'User', $this->userId )
                             ->willReturn( $updatedEntity );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( $updatedEntity->getAttributes() );

        $this->object->update( $this->userId, $this->requestData );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfRecordIsNotFound()
    {

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'findByPk' )
                             ->with( 'User', $this->userId )
                             ->willReturn( null );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( 'Record not Found.' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_RESOURCE_NOT_FOUND );

        $this->object->update( $this->userId, $this->requestData );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfRecordIsNotPersisted()
    {
        /** @var User $existingEntity */
        $existingEntity = $this->getUserEntity( $this->userCurrentAttributes );

        $this->repositoryMock->expects( $this->at( 0 ) )
                             ->method( 'findByPk' )
                             ->with( 'User', $this->userId )
                             ->willReturn( $existingEntity );

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'save' )
                             ->willThrowException( new Exception() );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( 'Record Not Saved.' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_SERVER_ERROR );

        $this->object->update( $this->userId, $this->requestData );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfUserEntityHasErrors()
    {
        $object = $this->object;
        $entityErrors = ['User' => ['attribute1' => 'Required']];

        /** @var User $existingEntity */
        $existingEntity = $this->getUserEntity( $this->userCurrentAttributes );

        $this->validatorMock->expects( $this->any() )
                            ->method( 'getErrors' )
                            ->willReturn( $entityErrors );

        $this->repositoryMock->expects( $this->never() )
                             ->method( 'save' );

        $this->repositoryMock->expects( $this->any() )
                             ->method( 'findByPk' )
                             ->with( 'User', $this->userId )
                             ->willReturn( $existingEntity );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( $entityErrors );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_BAD_REQUEST );

        $this->object->update( $this->userId, $this->requestData );
    }

    /**
     * @param $userAttributes
     *
     * @return array
     */
    protected function getUserEntity( $userAttributes )
    {

        $userEntity = new User();
        $userEntity->setAttributes( $userAttributes );

        return $userEntity;
    }

}
