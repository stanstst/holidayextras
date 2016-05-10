<?php

namespace Test\Integration\Helper;

use Application\Delivery\Web\Request;

class RequestDoubleAgent extends Request
{
    protected $request;

    public function __construct( $content )
    {
        $this->request = $content;
    }

    public function getPayload()
    {

        return $this->request;
    }
}