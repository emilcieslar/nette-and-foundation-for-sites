<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use App\Model\Repository\PassLinkRepository;

class NewPasswordFormFactory extends Nette\Object
{
	/** @var FormFactory */
	private $factory;

	/** @var Database\Context */
	private $db;

	/** @var PassLinkRepository */
	private $passLinkRepository;

	public function __construct(
		FormFactory $factory,
		Nette\Database\Context $database,
		PassLinkRepository $passLinkRepository)
	{
		$this->factory = $factory;
		$this->db = $database;
		$this->passLinkRepository = $passLinkRepository;
	}

	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

		$form->addPassword('password', 'Heslo:')
		->addRule(Form::MIN_LENGTH, 'Heslo musí být alespoň %d znaků dlouhé', 8)
		->setRequired('Prosím vyplňte heslo');

		$form->addPassword('password_again', 'Heslo znovu:')
		->addRule(Form::EQUAL, 'Hesla se musí shodovat', $form['password'])
		->setRequired('Prosím vyplňte heslo pro kontrolu ještě jednou');

		$form->addSubmit('send', 'Vytvořit nové heslo');

		$form->addProtection('Vypršela platnost tohoto formuláře, prosím aktualizujte stránku.');

        $form->onSuccess[] = array($this, 'formSucceeded');

		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		// Get password creation link from presenter's url
		$link = $form->getPresenter()->getParameters()['id'];

		// Get user associated with password creation link
		// (if the link is still valid and we have a user associated with it)
		if($user = $this->passLinkRepository->getLinkUser($link)) {

			// Create password for the user
			$user->update([
				'password' => Passwords::hash($values->password)
			]);

			// When the password is successfully created, invalidated the link
			$this->passLinkRepository->deleteLink($link);

			// Send a flash message
			$form->getPresenter()->flashMessage('Heslo úspěšně vytvořeno, můžete se přihlásit', 'success');

			// Redirect to admin login
			$form->getPresenter()->redirect(':Admin:Login:default');

		// In case any error during getting the user occurs, redirect to homepage
		} else {

			// Send a flash message
			$form->getPresenter()->flashMessage('Odkaz na vytvoření nového hesla již není platný', 'alert');

			// Redirect to home
			$form->getPresenter()->redirect(':Front:Homepage:default');

		}
	}

}
