<?php

namespace Application\Interactor\User;

use Application\Delivery\Response;
use Application\Entity\ValidatorFactory;
use Application\Persistence\Mysql\DoctrineRepository;
use Application\Persistence\Repository;

class CreatorFactory
{
    /**
     * @param Response $response
     *
     * @return Creator
     */
    public function create( Response $response )
    {
        /** @var Repository $repository */
        $repository = DoctrineRepository::instance();

        return new Creator( $response,
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