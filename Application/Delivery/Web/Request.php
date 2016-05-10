<?php

namespace Application\Delivery\Web;

use Application\Delivery\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * @return mixed []
     * @throws \Exception
     */
    public function getPayload()
    {
        $json = file_get_contents('php://input');
        $request = json_decode($json, true);

        return $request;
    }
}