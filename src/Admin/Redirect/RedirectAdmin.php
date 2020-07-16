<?php

declare(strict_types=1);

namespace App\Admin\Redirect;

use App\Entity\Redirect\Redirect;
use Sonata\AdminBundle\Form\FormMapper;
use App\Service\Cache\RedirectCacheBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Templating\TemplateRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class RedirectAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'app/redirect';
    protected $baseRouteName = 'app/redirect';
    protected $cacheredirect;

    // Translations
    protected $t_source = 'label.redirect.source';
    protected $t_destination = 'label.redirect.destination';
    protected $t_httpcode = 'label.redirect.httpcode';
    protected $t_publish = 'label.redirect.publish';

    private static $typeChoices = [
        'redirect.httpCode.permanent' => Redirect::PERMANENT,
        'redirect.httpCode.temporal' => Redirect::TEMPORAL,
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('source', null, ['label' => $this->t_source])
            ->add('destination', null, ['label' => $this->t_destination])
            ->add('httpCode', null, ['label' => $this->t_httpcode], ChoiceType::class, [
                'choices' => self::$typeChoices,
            ])
            ->add('publish', null, ['label' => $this->t_publish]);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('source', 'url', [
                'label' => $this->t_source,
                'hide_protocol' => true,
                'attributes' => [
                    'target' => '_blank',
                ],
            ])
            ->add('destination', 'url', [
                'label' => $this->t_destination,
                'hide_protocol' => true,
                'attributes' => [
                    'target' => '_blank',
                ],
            ])
            ->add('httpCode', TemplateRegistry::TYPE_CHOICE, [
                'label' => $this->t_httpcode,
                'choices' => array_flip(self::$typeChoices),
                'editable' => true,
                'catalogue' => 'messages',
            ])
            ->add('publish', null, [
                'label' => $this->t_publish,
                'editable' => true,
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [
                        'template' => 'admin/CRUD/list__action_edit.html.twig',
                    ],
                    'delete' => [
                        'template' => 'admin/CRUD/list__action_delete.html.twig',
                    ],
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab('header.redirection')
                ->with('redirection.source.destination', [
                    'class'       => 'col-md-8',
                ])
                    ->add('source', TextType::class, [
                        'label' => $this->t_source,
                        'row_attr' => ['class' => 'col-md-6'],
                        'attr' => ['placeholder' => 'help.redirect.source']
                    ])
                    ->add('destination', TextType::class, [
                        'label' => $this->t_destination,
                        'row_attr' => ['class' => 'col-md-6'],
                        'attr' => ['placeholder' => 'help.redirect.destination']
                    ])
                    
                ->end()
                ->with('redirect.choice.httpcode', [
                    'class'       => 'col-md-4',
                ])
                    ->add('httpCode', ChoiceType::class, [
                        'choices' => self::$typeChoices,
                        'label' => false,
                        'expanded' => true,
                    ])
                ->end()
                ->with('redirect.choice.publish', [
                    'class'       => 'col-md-4',
                ])
                    ->add('publish', CheckboxType::class, ['label' => $this->t_publish, 'required' => false])
                ->end()
            ->end();
    }

    public function prePersist($object)
    {
        parent::prePersist($object);
        
        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->cacheredirect = new RedirectCacheBuilder($em, $cache);

        $verif = (true == $object->getPublish()) ? true : false;

        if($verif) {
            $this->cacheredirect->update($object->getSource(), $object->getDestination(), $object->getHttpCode());
        } else {
            $this->cacheredirect->remove($object->getSource());
        }
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);

        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->cacheredirect = new RedirectCacheBuilder($em, $cache);

        $verif = (true == $object->getPublish()) ? true : false;

        if($verif) {
            $this->cacheredirect->update($object->getSource(), $object->getDestination(), $object->getHttpCode());
        } else {
            $this->cacheredirect->remove($object->getSource());
        }
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->cacheredirect = new RedirectCacheBuilder($em, $cache);

        $verif = (true == $object->getPublish()) ? true : false;

        if($verif) {
            $this->cacheredirect->update($object->getSource(), $object->getDestination(), $object->getHttpCode());
        } else {
            $this->cacheredirect->remove($object->getSource());
        }
    }

    public function preRemove($object)
    {
        parent::preRemove($object);

        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->cacheredirect = new RedirectCacheBuilder($em, $cache);

        $this->cacheredirect->remove($object->getSource());
    }

    public function postRemove($object)
    {
        parent::postRemove($object);

        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->cacheredirect = new RedirectCacheBuilder($em, $cache);

        $this->cacheredirect->remove($object->getSource());
    }
}
