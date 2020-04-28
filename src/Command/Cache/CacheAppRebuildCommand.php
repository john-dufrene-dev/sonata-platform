<?php

namespace App\Command\Cache;

use App\Entity\Redirect\Redirect;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Configuration\Configuration;
use App\Service\Cache\RedirectCacheBuilder;
use Symfony\Component\Console\Command\Command;
use App\Service\Cache\ConfigurationCacheBuilder;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheAppRebuildCommand extends Command
{
    protected static $defaultName = 'cache-app:rebuild';

    /**
     * em
     *
     * @var mixed
     */
    protected $em;

    /**
     * cacheredirect
     *
     * @var mixed
     */
    protected $cacheredirect;

    protected $config;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        EntityManagerInterface $em,
        RedirectCacheBuilder $cacheredirect,
        ConfigurationCacheBuilder $config
    ) {
        $this->em = $em;
        $this->cacheredirect = $cacheredirect;
        $this->config = $config;

        parent::__construct();
    }
    
    /**
     * configure
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('Rebuild all cache app')
            ->setHelp('This command allows you to rebuild all cache app');
    }
    
    /**
     * execute
     *
     * @param  mixed $input
     * @param  mixed $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $success_configurations = true;
        $success_redirects = true;
        $configs = $this->em->getRepository(Configuration::class);
        $redirects = $this->em->getRepository(Redirect::class);

        $output->writeln([
            '',
            '========================================================',
            '<comment>Start rebuild cache app configuration</comment>',
            '========================================================',
            '',
        ]);

        // Rebuild all configuration in cache app
        foreach ($configs->findAll() as $config) {
            $output->writeln('Rebuild cache app configuration for ' . $config->getName());
            if ($config) {
                $this->config->update($config->getName(), $config->getValue());
                // $io->success('Successfull rebuid cache app configuration with value ' . $config->getName());
            } else {
                $success_configurations = false;
                $io->error('Error when trying to rebuid cache app configuration with value ' . $config->getName());
            }
        }

        if ($success_configurations) {
            $io->success('Successfull rebuid cache app configuration !');
        }

        $output->writeln([
            '',
            '===================================================',
            '<comment>Start rebuild cache app redirect</comment>',
            '===================================================',
            '',
        ]);

        // Rebuild all redirect in cache app
        foreach ($redirects->findAll() as $redirect) {
            $output->writeln('Rebuild cache app redirect for ' . $redirect->getSource() . ' to '
                . $redirect->getDestination() . 'with http code ' . $redirect->getHttpCode());
            if ($redirect) {
                $this->cacheredirect->update(
                    $redirect->getSource(),
                    $redirect->getDestination(),
                    $redirect->getHttpCode()
                );
                // $io->success('Successfull rebuid cache app redirect with value ' . $redirect->getSource());
            } else {
                $success_redirects = false;
                $io->error('Error when trying to rebuid cache app redirect with value ' . $redirect->getSource());
            }
        }

        $output->writeln([
            '',
        ]);

        if ($success_redirects) {
            $io->success('Successfull rebuid cache app redirect !');
        }

        $output->writeln([
            '',
            '===================================================',
            '<comment>SUCCESS !</comment>',
            '===================================================',
            '',
        ]);

        $io->success('All cache app are rebuild !');

        return 0;
    }
}
