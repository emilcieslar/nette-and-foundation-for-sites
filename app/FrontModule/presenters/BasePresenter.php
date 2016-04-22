<?php

namespace FrontModule;

use Nette;


/**
* Base presenter for all application presenters.
*/
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    protected $db;

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->db = $database;
    }

}
