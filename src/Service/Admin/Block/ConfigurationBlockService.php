<?php

namespace App\Service\Admin\Block;

use Twig\Environment;
use App\Entity\Configuration;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\Configuration\ConfigurationBuilder;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * router
     *
     * @var mixed
     */
    protected $router;

    /**
     * translator
     *
     * @var mixed
     */
    protected $translator;

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
    protected $cache_configs;

    /**
     * configs
     *
     * @var mixed
     */
    protected $configs;

    public function __construct(
        Environment $twig, 
        $templating = null,
        ContainerBag $params,
        EntityManagerInterface $em,
        AdapterInterface $cache,
        RequestStack $request,
        RouterInterface $router,
        TranslatorInterface $translator
    ) {
        parent::__construct($twig, $templating);
        $this->params = $params;
        $this->em = $em;
        $this->cache = $cache;
        $this->request = $request;
        $this->router = $router;
        $this->translator = $translator;
    }

    protected $template = 'admin/block/configuration_template.html.twig';

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'title' => 'You need to change this value',
            'configs' => [],
            'btn_title_extra' => null, // Add extra button title
            'btn_id_extra' => null, // Add extra button id
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
        $this->cache_configs = [];
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
            foreach($this->request->getCurrentRequest()->request->all() as $key => $value) {
                $this->config->update($key, $value);
            }

            $this->request->getCurrentRequest()->getSession()
                ->getFlashBag()->add('success', $this->translator->trans('custom.block.service.flash.success'));

            return new RedirectResponse('list');
        }

        return $this->renderResponse($blockContext->getTemplate(), [
            'configs' => $this->cache_configs,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}