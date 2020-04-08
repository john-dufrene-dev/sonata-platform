<?php

namespace App\DataFixtures;

use App\Entity\UserAdmin\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin;
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.fr');
        $admin->setEnabled(true);
        $admin->setPlainPassword('admin');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
