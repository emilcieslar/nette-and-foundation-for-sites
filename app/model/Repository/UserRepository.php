<?php

namespace App\Model\Repository;

class UserRepository extends Repository
{
    public function getUserByUsername($username)
    {
        return $this->db->table('users')
            ->where('username = ', $username)
            ->fetch();
    }
}
