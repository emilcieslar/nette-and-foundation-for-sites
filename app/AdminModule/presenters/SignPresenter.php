<?php

namespace AdminModule;

use Nette;
use App\Forms\SignFormFactory;

class SignPresenter extends Nette\Application\UI\Presenter
{
    /** @var SignFormFactory @inject */
	public $factory;

    public function actionDefault()
    {
        if($this->user->isLoggedIn()) {
            $this->redirect('Homepage:default');
        } else {
            $this->redirect('Sign:in');
        }
    }

    public function actionOut()
    {
        $this->user->logout();
        $this->flashMessage('Byli jste úspěšně odhlášeni.');
        $this->redirect('Sign:in');
    }

    /**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};
		return $form;
	}

}
