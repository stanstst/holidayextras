<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require 'config.php';

try
{
    $controllerFactory = Application\Utils\ControllerFactory::instance( $config );
    $controller = $controllerFactory->create( $_SERVER );
    $controllerAction = $controllerFactory->getControllerAction();
    $controller->$controllerAction();

    http_response_code( $controller->getResponse()
                                   ->getResponseCode() );
    echo json_encode( $controller->getResponse()
                                 ->getOutput() );
}
catch( \Exception $exception )
{
    echo 'Error: ' . $exception->getMessage();
}


