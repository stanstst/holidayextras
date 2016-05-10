<?php

namespace Test\Integration;

use Application\Controller\User;
use Application\Utils\ControllerFactory;
use Test\Integration\Helper\RequestDoubleAgent;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    /**
     * @var ControllerFactory
     */
    protected $controllerFactory;

    /**
     * @var User
     */
    protected $controller;

    public function setUp()
    {
        $this->config = require __DIR__ . '/../../config.php';
        $this->controllerFactory = new ControllerFactory( $this->config );
        parent::setUp();
    }

    /**
     * @test
     */
    public function getUser()
    {
        $serverEnv = ['REQUEST_URI' => '/user/2', 'REQUEST_METHOD' => 'GET'];
        $controller = $this->controllerFactory->create( $serverEnv );
        $controllerAction = $this->controllerFactory->getControllerAction();
        $controller->$controllerAction();

        $actualOutput = $controller->getResponse()
                                   ->getOutput();
        $this->assertArraySubset( ['id' => 2], $actualOutput );

    }

    /**
     * @test
     */
    public function createUser()
    {
        $serverEnv = ['REQUEST_URI' => '/user', 'REQUEST_METHOD' => 'POST'];
        $this->setRequestBody( [
            'email'    => 'john@mail.com',
            'forename' => 'john',
            'surname'  => 'doe',
        ] );

        $controller = $this->controllerFactory->create( $serverEnv );
        $controllerAction = $this->controllerFactory->getControllerAction();
        $controller->$controllerAction();

        $actualOutput = $controller->getResponse()
                                   ->getOutput();
        $this->assertArraySubset( [
            'email'    => 'john@mail.com',
            'forename' => 'john',
            'surname'  => 'doe',
        ],
            $actualOutput );

    }

    /**
     * @test
     */
    public function updateUser()
    {
        $serverEnv = ['REQUEST_URI' => '/user/2', 'REQUEST_METHOD' => 'PUT'];
        $this->setRequestBody( [
            'email'    => 'johnnn@mail.com',
            'forename' => 'johnnn',
            'surname'  => 'doeee',
        ] );

        $controller = $this->controllerFactory->create( $serverEnv );
        $controllerAction = $this->controllerFactory->getControllerAction();
        $controller->$controllerAction();

        $actualOutput = $controller->getResponse()
                                   ->getOutput();

        $this->assertArraySubset( [
            'id'       => '2',
            'email'    => 'johnnn@mail.com',
            'forename' => 'johnnn',
            'surname'  => 'doeee',
        ],
            $actualOutput );

    }

    protected function setRequestBody( $requestData )
    {
        $reflectionClass = new \ReflectionClass( $this->controllerFactory );

        $reflectionProperty = $reflectionClass->getProperty( 'request' );
        $reflectionProperty->setAccessible( true );
        $reflectionProperty->setValue( $this->controllerFactory, new RequestDoubleAgent( $requestData ) );
    }
}
