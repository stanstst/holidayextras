<?php

namespace Test\Inreractor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Entity\Validator\Validator;
use Application\Interactor\User\Creator;
use Application\Persistence\Repository;
use Exception;
use \PHPUnit_Framework_TestCase;

class CreatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Creator
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

        $this->object = new Creator( $this->responseMock, $this->repositoryMock, $this->validatorMock );
        parent::setUp();
    }

    /**
     * @test
     */
    public function callSaveOnRepositoryWithNewUserEntity()
    {
        $requestData = [
            'email'    => 'john@mail.com',
            'forename' => 'john',
            'surname'  => 'doe',
        ];
        $createdDateTime = new \DateTime( '1000-00-00 10:00:00' );

        $this->setObjectCurrentDateTime( $createdDateTime );

        $expectedNewEntity = new User();
        $expectedNewEntity->setAttributes( $requestData );
        $expectedNewEntity->setCreated( $createdDateTime );

        $this->validatorMock->expects( $this->once() )
                            ->method( 'validate' );

        $this->repositoryMock->expects( $this->once() )
                             ->method( 'save' )
                             ->with( $expectedNewEntity );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with(  $expectedNewEntity->getAttributes() );

        $this->object->create( $requestData );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfUserEntityIsNotSaved()
    {
        $object = $this->object;
        $this->repositoryMock->expects( $this->once() )
                             ->method( 'save' )
                             ->willThrowException( new Exception( '' ) );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( $object::USER_NOT_SAVED_ERROR );
        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_SERVER_ERROR );

        $this->object->create( [] );
    }

    /**
     * @test
     */
    public function addsErrorInResponseIfUserEntityHasErrors()
    {
        $object = $this->object;
        $entityErrors = ['User' => ['attribute1' => 'Required']];

        $this->validatorMock->expects( $this->any() )
                            ->method( 'getErrors' )
                            ->willReturn( $entityErrors );

        $this->repositoryMock->expects( $this->never() )
                             ->method( 'save' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( $entityErrors );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_BAD_REQUEST );

        $this->object->create( [] );
    }

    private function setObjectCurrentDateTime( $dateTimeValue )
    {
        $reflectionClass = new \ReflectionClass( $this->object );

        $reflectionProperty = $reflectionClass->getProperty( 'currentDateTime' );
        $reflectionProperty->setAccessible( true );
        $reflectionProperty->setValue( $this->object, $dateTimeValue );
    }

}
