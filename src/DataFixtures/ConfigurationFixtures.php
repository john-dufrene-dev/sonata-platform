<?php

namespace App\DataFixtures;

use App\Entity\Configuration\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigurationFixtures extends Fixture
{
    protected $start = 'CONF_';

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function load(ObjectManager $manager)
    {
        /*
         *
         * Configuration for Maintenance Mode
         *
         */

        // Create configuration for list valid IP for maintenance
        $config = new Configuration;
        $config->setName($this->start.'MAINTENANCE_IP_VALID');
        $config->setValue('::1,127.0.0.1');
        $config->setTitle($this->translator->trans('configuration.filters.ip', [], 'fixtures'));
        $manager->persist($config);

        // Create configuration for status maintenance
        $config = new Configuration;
        $config->setName($this->start.'MAINTENANCE_STATUS');
        $config->setValue(1);
        $config->setTitle($this->translator->trans('configuration.enable.disable.ip', [], 'fixtures'));
        $config->setType('boolean');
        $manager->persist($config);

        /*
         *
         * Configuration for SEO
         *
         */

        $seo_values = ['HOME', 'REGISTER', 'ACCOUNT'];

        foreach($seo_values as $seo_value) {

            // Create configuration for SEO title
            $config = new Configuration;
            $config->setName($this->start.'SEO_'.$seo_value.'_TITLE');
            $config->setValue($this->translator->trans('seo.'.strtolower($seo_value).'.value.title', [], 'fixtures'));
            $config->setTitle($this->translator->trans('seo.'.strtolower($seo_value).'.title.title', [], 'fixtures'));
            $manager->persist($config);

            // Create configuration for SEO description
            $config = new Configuration;
            $config->setName($this->start.'SEO_'.$seo_value.'_DESCRIPTION');
            $config->setValue($this->translator->trans('seo.'.strtolower($seo_value).'.value.description', [], 'fixtures'));
            $config->setTitle($this->translator->trans('seo.'.strtolower($seo_value).'.title.description', [], 'fixtures'));
            $manager->persist($config);

            // Create configuration for SEO keywords
            $config = new Configuration;
            $config->setName($this->start.'SEO_'.$seo_value.'_KEYWORDS');
            $config->setTitle($this->translator->trans('seo.'.strtolower($seo_value).'.title.keywords', [], 'fixtures'));
            $manager->persist($config);

            // Create configuration for SEO indexation
            $config = new Configuration;
            $config->setName($this->start.'SEO_'.$seo_value.'_NO_INDEXATION');
            $config->setValue(0);
            $config->setTitle($this->translator->trans('seo.'.strtolower($seo_value).'.title.no.indexation', [], 'fixtures'));
            $config->setType('boolean');
            $manager->persist($config);
        }

        /*
         *
         * Configuration for Extensions
         *
         */

        // Create configuration to verify if Account extension is active
        $config = new Configuration;
        $config->setName($this->start.'EXTENSION_ACCOUNT_ACTIVE');
        $config->setValue(1);
        $config->setTitle($this->translator->trans('configuration.extension.account.active', [], 'fixtures'));
        $config->setType('boolean');
        $manager->persist($config);

        // Create configuration to verify if Account extension is active
        $config = new Configuration;
        $config->setName($this->start.'EXTENSION_API_ACTIVE');
        $config->setValue(1);
        $config->setTitle($this->translator->trans('configuration.extension.api.active', [], 'fixtures'));
        $config->setType('boolean');
        $manager->persist($config);

        $manager->flush();
    }
}
