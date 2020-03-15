<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ConfigurationFixtures extends Fixture
{
    protected $start = 'CONF_';

    public function load(ObjectManager $manager)
    {
        // Create configuration for list valid IP for maintenance
        $config = new Configuration;
        $config->setName($this->start.'MAINTENANCE_IP_VALID');
        $config->setValue('::1,127.0.0.1');
        $config->setTitle('Filtrage des adresses IP');
        $manager->persist($config);

        $manager->flush();

        // Create configuration for status maintenance
        $config = new Configuration;
        $config->setName($this->start.'MAINTENANCE_STATUS');
        $config->setValue(1);

        $config->setTitle('Activer / Désactiver le mode maintenance');
        $config->setType('boolean');
        $manager->persist($config);

        $manager->flush();
    }
}
