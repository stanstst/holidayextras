<?php

namespace Application\Delivery;


class Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;


    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __construct( Request $request, Response $response )
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

}