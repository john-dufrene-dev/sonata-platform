<?php

namespace App\DataFixtures;

use App\Entity\Security\AdminUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new AdminUser;
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.fr');
        $admin->setEnabled(true);
        $admin->setPlainPassword('admin');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
