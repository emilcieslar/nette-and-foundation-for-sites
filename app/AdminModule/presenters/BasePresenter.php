<?php

namespace AdminModule;

use Nette;

/**
* Base presenter for all application presenters.
* @resource Admin
*/
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected $db;

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->db = $database;
    }

    /**
     * Check authorization
     * @return void
     */
    public function checkRequirements($element) {
        if($element instanceof Nette\Reflection\Method) {
            /**
             * Check permissions for Action/handle methods
             *
             *  - Do not that (rely on presenter authorization)
             */
            return;
        } else {
            $resource = $element->getAnnotation('resource');
        }

        // First of all, check if user is even logged in
        if(!$this->user->isLoggedIn()) {

            if ($this->user->getLogoutReason() === Nette\Security\IUserStorage::INACTIVITY) {
				$this->flashMessage('Byl jste odhlášen z důvodu neaktivity. Prosím, přihlašte se znovu.');
			}

            // If not, redirect to login
            $this->redirect('Sign:in', ['backlink' => $this->storeRequest()]);

        // If user is logged in, check if the user is authorized to access this section (Admin section)
        // In other circumstances, we would pass $resource into isAllowed() method, which contains
        // @resource annotation, however in this case, the whole module is devoted to 'admin' role so
        // we don't have to use @resource on every presenter in this module, we just check if this user
        // role is allowed to acces 'Admin' resource
        } elseif(!$this->user->isAllowed('Admin')) {
            throw new Nette\Application\ForbiddenRequestException;
        }

    }

}
