<?php

namespace AdminModule;

use Nette;
use App\Forms\SignFormFactory;

class LoginPresenter extends Nette\Application\UI\Presenter
{
    /** @var SignFormFactory @inject */
	public $factory;

    public function actionDefault()
    {
        if($this->user->isLoggedIn()) {
            $this->redirect('Homepage:default');
        }
    }

    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Byli jste úspěšně odhlášeni.');
        $this->redirect('Login:default');
    }

    /**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentLoginForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}

}
