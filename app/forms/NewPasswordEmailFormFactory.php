<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use App\Model\Repository\UserRepository;
use App\Model\Repository\PassLinkRepository;

class NewPasswordEmailFormFactory extends Nette\Object
{
	/** @var FormFactory */
	private $factory;

	/** @var Database\Context */
	private $db;

	/** @var Array */
	private $config;

	/** @var UserRepository */
	private $userRepository;

	/** @var PassLinkRepository */
	private $passLinkRepository;

	public function __construct(
		FormFactory $factory,
		Nette\Database\Context $database,
		Nette\DI\Container $container,
		UserRepository $userRepository,
		PassLinkRepository $passLinkRepository)
	{
		$this->factory = $factory;
		$this->db = $database;
		$this->config = $container->parameters;
		$this->userRepository = $userRepository;
		$this->passLinkRepository = $passLinkRepository;
	}

	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

		$form->addText('email', 'Vaše emailová adresa:')
			->setRequired('Prosím vyplňte emailovou adresu');

		$form->addSubmit('send', 'Zaslat odkaz na email');

		$form->addProtection('Vypršela platnost tohoto formuláře, prosím aktualizujte stránku.');

        $form->onSuccess[] = array($this, 'formSucceeded');

		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		// Get user by his email
		$user = $this->userRepository->getUserByUsername($values->email);

		// If such user exists
		if($user) {

			// Get user ID
			$user_id = $user->id;

			// Generate a new link
			$link = $this->passLinkRepository->generateLink($user_id);

			// Send email with the link
			$mail = new Message;
			$mail->setFrom($this->config['serverEmail'])
				->addReplyTo($this->config['serverEmail'])
				->addTo($values->email)
				->setSubject('Odkaz na vytvoření hesla')
				->setHtmlBody('Níže naleznete odkaz na vytvoření nového hesla. Odkaz je platný pouze 14 dní.<br><br><a href="' . $this->config['serverUrl'] . '/password/new/link/' . $link->hash . '">Vytvořit nové heslo</a>');

			$mailer = new SendmailMailer;
			$mailer->send($mail);

			$form->getPresenter()->flashMessage('Odkaz pro vytvoření nového hesla byl zaslán na Váš email', 'success');
			$form->getPresenter()->redirect('LinkGenerated');

			// Otherwise, error
		} else {
			$form->getPresenter()->flashMessage('Uživatelské jméno neexistuje', 'alert');
		}
	}

}
