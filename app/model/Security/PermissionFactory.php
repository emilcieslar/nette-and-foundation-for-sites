<?php

namespace App\Model\Security;

use Nette;
use Nette\Security\Permission;

class PermissionFactory extends Nette\Object
{
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * @return \Nette\Security\IAuthorizator
     */
    public static function create()
    {
        $permission = new Permission();

        /* seznam uživatelských rolí */
        $permission->addRole(self::ROLE_GUEST);
        $permission->addRole(self::ROLE_USER);
        $permission->addRole(self::ROLE_ADMIN, self::ROLE_USER);

        /* seznam zdrojů */
        $permission->addResource('Admin');

        /* seznam pravidel oprávnění */
        // user role má právo na vstup do ...
        //$permission->allow(self::ROLE_USER, 'MyResource');

        /* admin má práva na všechno */
        $permission->allow(self::ROLE_ADMIN, Permission::ALL, Permission::ALL);

        return $permission;
    }
}
