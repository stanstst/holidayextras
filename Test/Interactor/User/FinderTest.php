<?php

namespace Test\Inreractor\User;

use Application\Delivery\Response;
use Application\Entity\User;
use Application\Interactor\User\Finder;
use Application\Persistence\Repository;
use Exception;
use \PHPUnit_Framework_TestCase;

class FinderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Finder
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

        $this->object = new Finder( $this->responseMock, $this->repositoryMock );
        parent::setUp();
    }

    /**
     * @test
     */
    public function pushesFoundRecordAttributesInResponse()
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

        $this->responseMock->expects( $this->once() )
                           ->method( 'setPayload' )
                           ->with( $userAttributes );
        $this->object->find( $userId );
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
                           ->with( 'Record not Found.' );

        $this->responseMock->expects( $this->once() )
                           ->method( 'setResponseCode' )
                           ->with( Response::STATUS_RESOURCE_NOT_FOUND );
        $this->object->find( $userId );
    }

}
