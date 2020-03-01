<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $infos = new UserInfo();

        $user->setEmail('test@test.fr');
        $user->setRoles(['ROLE__USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test'
        ));
        $user->setInfos($infos);

        $manager->persist($user);

        $manager->flush();
    }
}
