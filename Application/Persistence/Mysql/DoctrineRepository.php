<?php

namespace Application\Persistence\Mysql;

use Application\Entity\Entity;
use Application\Persistence\Repository;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineRepository implements Repository
{
    const APPLICATION_ENTITY_NAMESPACE = '\Application\Entity\\';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct()
    {
        $config = require __DIR__ . '/../../../config.php';
        $paths = [$config['persistence']['entityYmlPath']];
        $isDevMode = true;

        // the connection configuration
        $dbParams = $config['doctrineParams'];

        $config = Setup::createYAMLMetadataConfiguration( $paths, $isDevMode );
        $this->entityManager = EntityManager::create( $dbParams, $config );
    }

    /**
     * @param Entity $entity
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function save( Entity $entity )
    {
        $this->entityManager->persist( $entity );
        $this->entityManager->flush();
    }

    /**
     * @param $entityName
     * @param $pk
     *
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findByPk( $entityName, $pk )
    {
        $entityClass = self::APPLICATION_ENTITY_NAMESPACE . $entityName;

        return $this->entityManager->find( $entityClass, $pk );
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return new static();
    }

    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public function delete( $entity )
    {
        $this->entityManager->remove( $entity );
        $this->entityManager->flush();
    }
}