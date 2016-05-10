<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Entity\ValidatorFactory;
use Application\Persistence\Mysql\DoctrineRepository;
use Application\Persistence\Repository;

class UpdaterFactory
{
    public function __construct()
    {
    }

    /**
     * @param Response $response
     *
     * @return Updater
     */
    public function create( Response $response )
    {
        /** @var Repository $repository */
        $repository = DoctrineRepository::instance();

        return new Updater( $response,
            $repository,
            ValidatorFactory::instance()
                            ->create( [
                                'email'    => ['Required', 'Email'],
                                'forename' => ['Required'],
                            ] ) );
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return new static();
    }

}