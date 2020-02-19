<?php

namespace App\Controller\Front\Pages;

use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    protected $seo;

    public function __construct(SeoPageInterface $seo)
    {
        $this->seo = $seo;
    }
    
    /**
     * @Route("/home", name="front.pages.home.index")
     */
    public function index()
    {
        $this->seo
            ->addTitle('Page d\'accueil') // you can use setTitle($title)
            ->addMeta('name', 'robots', 'index, follow')
            ->addMeta('name', 'description', 'Description page d\'accueil');

        return $this->render('front/pages/home/index.html.twig', [
            'page_name' => 'Page d\'accueil',
        ]);
    }
}
