<?php

namespace App\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a Base User Entity.
 */
class User extends BaseUser
{
    /**
     * Represents a Base User Entity.
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
