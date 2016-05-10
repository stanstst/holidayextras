<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Persistence\Mysql\DoctrineRepository;
use Application\Persistence\Repository;

class FinderFactory
{
    public function __construct()
    {
    }

    /**
     * @param Response $response
     *
     * @return Creator
     */
    public function create(Response $response)
    {
        /** @var Repository $repository */
        $repository = DoctrineRepository::instance();
        return new Finder($response, $repository);
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return new static();
    }

}