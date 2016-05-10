<?php

namespace Application\Controller;

use Application\Delivery\Controller;
use Application\Interactor\User\Creator;
use Application\Interactor\User\CreatorFactory;
use Application\Interactor\User\Deleter;
use Application\Interactor\User\DeleterFactory;
use Application\Interactor\User\Finder;
use Application\Interactor\User\FinderFactory;
use Application\Interactor\User\Updater;
use Application\Interactor\User\UpdaterFactory;

class User extends Controller
{
    public function create()
    {
        $requestData  = $this->request->getPayload();


        /** @var Creator $userCreator */
        $userCreator = CreatorFactory::instance()
                                     ->create( $this->response );

        $userCreator->create($requestData);
    }

    public function get()
    {
        /** @var Finder $userFinder */
        $userFinder = FinderFactory::instance()
                                   ->create( $this->response );

        $userFinder->find($this->request->getIdentifier());
    }

    public function update()
    {
        $requestData  = $this->request->getPayload();

        /** @var Updater $userUpdater */
        $userUpdater = UpdaterFactory::instance()
                                    ->create( $this->response );

        $userUpdater->update($this->request->getIdentifier(), $requestData);
    }

    public function delete()
    {
        /** @var Deleter $userDeleter */
        $userDeleter = DeleterFactory::instance()
                                   ->create( $this->response );

        $userDeleter->delete($this->request->getIdentifier());
    }

    public function getList()
    {
    }

    public function deleteAll()
    {
    }

}