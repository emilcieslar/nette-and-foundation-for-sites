<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignFormFactory extends Nette\Object
{
	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

        $form->addText('username', 'Uživatelské jméno:')
        	->setRequired('Prosím vyplňte své uživatelské jméno');

        $form->addPassword('password', 'Heslo:')
        	->setRequired('Prosím vyplňte heslo');

		$form->addCheckbox('remember', 'Zůstat přihlášený');

        $form->addSubmit('send', 'Přihlásit se');

        $form->addProtection('Vypršela platnost tohoto formuláře, prosím přihlašte se znovu.');

        $form->onSuccess[] = array($this, 'formSucceeded');

		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('2 hours', TRUE);
		}

		try {
			$this->user->login($values->username, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}

}
