<?php

declare(strict_types=1);

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Model\Group as BaseGroup;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Security\AdminGroupRepository")
 * @ORM\Table(name="admin_group")
 */
class AdminGroup extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
