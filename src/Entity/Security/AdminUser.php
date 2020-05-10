<?php

declare(strict_types=1);

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Security\AdminUserRepository")
 * @ORM\Table(name="admin")
 */
class AdminUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\AdminGroup")
     * @ORM\JoinTable(name="admin_admin_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
