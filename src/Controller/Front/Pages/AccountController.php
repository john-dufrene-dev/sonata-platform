<?php

namespace App\Controller\Front\Pages;

use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Cache\ConfigurationCacheBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{    
    /**
     * config
     *
     * @var mixed
     */
    protected $config;
    
    /**
     * seo
     *
     * @var mixed
     */
    protected $seo;

    public function __construct(SeoPageInterface $seo, ConfigurationCacheBuilder $config)
    {
        $this->config = $config;
        $this->seo = $seo;
    }

    /**
     * @Route("/account", name="account_index")
     * Require ROLE__USER for only this controller method.
     * @IsGranted("ROLE__USER")
     */
    public function index()
    {
        $valueSeo = $this->config->getSeoValue('ACCOUNT');

        $this->seo
            ->addTitle($valueSeo['title'] ?? '') // you can use setTitle($title)
            ->addMeta('name', 'robots', $valueSeo['index'] ?? '')
            ->addMeta('name', 'description', $valueSeo['description'] ?? '');

        return $this->render('front/pages/account/index.html.twig', [
            'page_name' => 'Mon compte',
        ]);
    }
}
