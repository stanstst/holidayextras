<?php

namespace Application\Utils;

use Application\Delivery\Controller;
use Application\Delivery\Request;
use Application\Delivery\Response;

class ControllerFactory
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $controllerAction;

    /**
     * @var string
     */
    protected $controllerNamespace = '\Application\Controller\\';

    /**
     * @var string
     */
    protected $actionMap;

    /**
     * @var string
     */
    protected $requestUri;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct( array $config )
    {
        $this->config = $config;

        $requestClassName = $this->config['delivery']['request'];
        $responseClassName = $this->config['delivery']['response'];

        /** @var Request $request */
        $this->request = new $requestClassName();
        $this->response = new $responseClassName();
    }

    /**
     * @param $serverEnv
     *
     * @return Controller
     * @throws \Exception
     */
    public function create($serverEnv)
    {
        $this->requestUri = $serverEnv['REQUEST_URI'];
        $this->requestMethod = $serverEnv['REQUEST_METHOD'];

        $serverQuery = explode( '/', substr( $this->requestUri, 1 ) );
        if( !$serverQuery[0] )
        {
            throw new \Exception( 'Bad request.' );
        }
        $resource = $serverQuery[0];
        $identifier = implode( '/', array_slice( $serverQuery, 1 ) );
        $httpMethod = $this->requestMethod;

        $this->getActionMap( $identifier, $httpMethod );

        $this->request->setIdentifier( $identifier );

        $controllerClassName = $this->controllerNamespace . ucfirst( $resource );

        return new $controllerClassName( $this->request, $this->response );
    }

    /**
     * @param $identifier
     * @param $httpMethod
     *
     * @throws \Exception
     */
    protected function getActionMap( $identifier, $httpMethod )
    {
        $actionMap = $this->config['restActions']['unIdentified'];
        if( $identifier )
        {
            $actionMap = $this->config['restActions']['identified'];
        }
        if( !$actionMap[strtolower( $httpMethod )] )
        {
            throw new \Exception( 'Action not supported.' );
        }

        $this->controllerAction = $actionMap[strtolower( $httpMethod )];
    }

    /**
     * @param array $config
     *
     * @return static
     */
    public static function instance( array $config )
    {
        return new static( $config );
    }

    /**
     * @return mixed
     */
    public function getControllerAction()
    {
        return $this->controllerAction;
    }
}