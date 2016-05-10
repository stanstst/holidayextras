<?php

namespace Test\Inreractor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Interactor\User\Deleter;
use Application\Persistence\Repository;
use \PHPUnit_Framework_TestCase;

class DeleterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Deleter
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

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder( Response::class )
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->repositoryMock = $this->getMockBuilder( Repository::class )
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->object = new Deleter( $this->responseMock, $this->repositoryMock );
        parent::setUp();
    }

    /**
     * @test
     */
    public function callsDeleteOnRepositoryWithFoundRecord()
    {
        $userId = 123;

        $userAttributes = [
            'id'       => $userId,
            'email'    => 'john@mail.com',
            'forename' => 'john',
            'surname'  => 'doe',
            'created'  => new \DateTime(),
        ];

        $userEntity = new User();
        $userEntity->setAttributes( $userAttributes );

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'findByPk' )
                             ->with( 'User', $userId )
                             ->willReturn( $userEntity );

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'delete' )
                             ->with( $userEntity );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_NO_CONTENT );

        $this->object->delete( $userId );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfUserRecordIsNotFound()
    {
        $userId = 123;

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'findByPk' )
                             ->with( 'User', $userId )
                             ->willReturn( null );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( 'Record not found.' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_RESOURCE_NOT_FOUND );

        $this->object->delete( $userId );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfUserRecordIsNotDeleted()
    {
        $userId = 123;
        $userEntity = new User();

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'findByPk' )
                             ->with( 'User', $userId )
                             ->willReturn( $userEntity );

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'delete' )
                             ->with( $userEntity )
                             ->willThrowException( new \Exception() );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( 'Record not deleted.' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_SERVER_ERROR );

        $this->object->delete( $userId );
    }

}
