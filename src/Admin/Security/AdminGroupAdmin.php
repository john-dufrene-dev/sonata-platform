<?php

declare(strict_types=1);

namespace App\Admin\Security;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use App\Form\Admin\Type\CustomSecurityRolesType;
use Nucleos\UserAdminBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

final class AdminGroupAdmin extends BaseGroupAdmin
{
    public $supportsPreviewMode = true;

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('name')
            ->add('roles', null, [
                'header_class' => 'col-md-6',
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [
                        'template' => 'admin/CRUD/list__action_show.html.twig',
                    ],
                    'edit' => [
                        'template' => 'admin/CRUD/list__action_edit.html.twig',
                    ],
                    'delete' => [
                        'template' => 'admin/CRUD/list__action_delete.html.twig',
                    ],
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab('form.tab_group')
                ->with('form.group_general', ['class' => 'col-md-6'])
                    ->add('name')
                ->end()
            ->end()

            ->tab('form.tab_security')
                ->with('form.group_roles', ['class' => 'col-md-12'])
                    ->add('roles', CustomSecurityRolesType::class, [
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                    ])
                ->end()
            ->end()
        ;
    }
}
