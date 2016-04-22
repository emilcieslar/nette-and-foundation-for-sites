<?php

namespace App\Model\Repository;

use Nette;

abstract class Repository extends Nette\Object
{
    /** @var Nette\Database\Context */
    protected $db;

    public function __construct(Nette\Database\Context $database)
    {
        $this->db = $database;
    }
}
