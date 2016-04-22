<?php

namespace App\Model\Repository;

class PassLinkRepository extends Repository
{
    public function generateLink($user_id)
    {
        // Create hash that will form a link to create a new password
        $bytes = openssl_random_pseudo_bytes(14);
        $hash = bin2hex($bytes);

        // Create a new pass-link in database and return it
        return $this->db->table('pass_link')->insert([
            'user_id' => $user_id,
            'hash' => $hash
        ]);

    }

    public function isLinkValid($linkHash)
    {
        return $this->db->table('pass_link')->where('hash = ? AND valid_until > CURRENT_TIMESTAMP', $linkHash)->limit(1)->fetch();
    }

    public function getLinkUser($linkHash)
    {
        // Get link (if there's any)
        $link = $this->db->table('pass_link')->where('hash = ? AND valid_until > CURRENT_TIMESTAMP', $linkHash)->limit(1)->fetch();

        // Check if we've got any, return the user associated with that link
        if($link) {
            return $link->user;

        // Otherwise return false
        } else {
            return false;
        }
    }

    public function deleteLink($linkHash)
    {
        return $this->db->table('pass_link')->where('hash = ?', $linkHash)->delete();
    }
}
