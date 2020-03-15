<?php

namespace App\Service\Admin\Block;

use App\Entity\Configuration;
use App\Service\ConfigurationBuilder;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;

final class ConfigurationBlockService  extends AbstractBlockService
{
    /**
     * params
     *
     * @var mixed
     */
    protected $params;
    
    /**
     * em
     *
     * @var mixed
     */
    protected $em;

    /**
     * cache
     *
     * @var mixed
     */
    protected $cache;

    /**
     * request
     *
     * @var mixed
     */
    protected $request;

    /**
     * config
     *
     * @var mixed
     */
    protected $config;

    /**
     * cache_configs
     *
     * @var mixed
     */
    protected $cache_configs = [];

    /**
     * configs
     *
     * @var mixed
     */
    protected $configs;

    public function __construct(
        string $service, 
        DelegatingEngine $templating, // For the moment use this depreciated class, wait for next version with Twig\Environment
        ContainerBag $params,
        EntityManagerInterface $em,
        AdapterInterface $cache,
        RequestStack $request
    ) {
        parent::__construct($service, $templating);
        $this->params = $params;
        $this->em = $em;
        $this->cache = $cache;
        $this->request = $request;
    }

    protected $template = 'admin/block/configuration_template.html.twig';

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'title' => 'You need to change this value',
            'configs' => [],
            'template' => $this->template,
        ]);
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->add('settings', 'sonata_type_immutable_array', [
                'keys' => [
                    ['title', 'text', ['required' => false]],
                ]
            ])
        ;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.title')
                ->assertNotNull([])
                ->assertNotBlank()
                ->assertMaxLength(['limit' => 50])
            ->end()
        ;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $this->config = new ConfigurationBuilder($this->em, $this->cache);
        $settings = $blockContext->getSettings();
        $this->configs = false;

        if($settings['configs'] && count($settings['configs'])) {
            foreach($settings['configs'] as $value) {

                $entity = $this->em->getRepository(Configuration::class);
                $result = $entity->findOneBy([
                    'name' => $value,
                    'enabled' => true,
                ]);

                if($result) {
                    $this->configs = $value;
                    if(null != $this->config->get($this->configs)) {
                        $options = [];
                        $options['name']        = $result->getName();
                        $options['value']       = $result->getValue();
                        $options['title']       = $result->getTitle();
                        $options['description'] = $result->getDescription();
                        $options['type']        = $result->getType();
                        array_push($this->cache_configs, $options);
                    }
                }
            }
        }

        if($this->request->getCurrentRequest()->isMethod('POST')) {
            dd($this->request->getCurrentRequest()->request->all());
        }

        return $this->renderResponse($blockContext->getTemplate(), [
            'configs' => $this->cache_configs,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}