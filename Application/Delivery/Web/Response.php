<?php

namespace Application\Delivery\Web;

use Application\Delivery\Response as IResponse;

class Response implements IResponse
{
    /**
     * @var mixed
     */
    protected $payload;

    /**
     * @var int
     */
    protected $responseCode = self::STATUS_OK;

    /**
     * @param mixed $payload
     *
     * @return null
     */
    public function setPayload( $payload )
    {
        $this->payload = $payload;
    }

    public function getOutput()
    {
        return $this->payload;
    }

    /**
     * @param int $responseCode
     *
     * @return null
     */
    public function setResponseCode( $responseCode )
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
}