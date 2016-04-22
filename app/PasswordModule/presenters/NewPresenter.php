<?php

namespace PasswordModule;

use Nette;
use App\Forms\NewPasswordFormFactory;
use App\Forms\NewPasswordEmailFormFactory;
use App\Model\Repository\PassLinkRepository;

class NewPresenter extends BasePresenter
{
	private $db;

	/** @var NewPasswordFormFactory @inject */
	public $newPasswordFactory;

	/** @var NewPasswordEmailFormFactory @inject */
	public $newPasswordEmailFactory;

	/** @var PassLinkRepository **/
	private $passLinkRepository;

	public function __construct(Nette\Database\Context $database, PassLinkRepository $passLinkRepository)
	{
		$this->db = $database;
		$this->passLinkRepository = $passLinkRepository;
	}

	public function renderDefault()
	{
		// If user lands to a deefault page, there's nothing to see, redirect to homepage
		$this->redirect(':Front:Homepage:default');
	}

	/**
	 * User arrives to this action through clicking on email link to create a new password
	 * @param String id - unique hash for a user to generate a new password
	 */
	public function actionLink($id)
	{
		if($id)
		{
			// Find out if this link is still valid, if not, redirect
			if(!$this->passLinkRepository->isLinkValid($id)) {
				$this->flashMessage('Tento odkaz na vytvoření nového hesla již není platný. Prosím pošlete si nový', 'alert');
				$this->redirect(':Password:New:generateLink');
			}

		} else {
			$this->redirect(':Front:Homepage:default');
		}
	}

	protected function createComponentPasswordForm()
	{
		$form = $this->newPasswordFactory->create();
		return $form;
	}

	protected function createComponentEmailForm()
	{
		$form = $this->newPasswordEmailFactory->create();
		return $form;
	}

}
