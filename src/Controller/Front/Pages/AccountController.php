<?php

namespace App\Controller\Front\Pages;

use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    protected $seo;

    public function __construct(SeoPageInterface $seo)
    {
        $this->seo = $seo;
    }
    
    /**
     * @Route("/account", name="account_index")
     * Require ROLE__USER for only this controller method.
     * @IsGranted("ROLE__USER")
     */
    public function index()
    {
        $this->seo
            ->addTitle('Page mon compte') // you can use setTitle($title)
            ->addMeta('name', 'robots', 'index, follow')
            ->addMeta('name', 'description', 'Description mon compte');

        return $this->render('front/pages/account/index.html.twig', [
            'page_name' => 'Mon compte',
        ]);
    }
}
