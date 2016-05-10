<?php

namespace Application\Delivery;

interface Response
{
    const STATUS_OK = 200;

    const STATUS_NO_CONTENT = 204;

    const STATUS_RESOURCE_NOT_FOUND = 404;

    const STATUS_BAD_REQUEST = 400;

    const STATUS_SERVER_ERROR = 500;

    /**
     * @param mixed $payload
     *
     * @return null
     */
    public function setPayload( $payload );

    /**
     * @param int $responseCode
     *
     * @return null
     */
    public function setResponseCode( $responseCode );

    /**
     * @return []
     */
    public function getOutput();

    /**
     * @return int
     */
    public function getResponseCode();

}