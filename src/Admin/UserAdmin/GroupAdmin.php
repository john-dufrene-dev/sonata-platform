<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Admin\UserAdmin;


use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\UserBundle\Admin\Model\GroupAdmin as BaseGroupAdmin;

final class GroupAdmin extends BaseGroupAdmin
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
                'header_style' => 'width: 50%',
            ])
            ->add('_action', null, [
                'actions' => [
                    'show' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_show.html.twig',
                    ],
                    'edit' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_edit.html.twig',
                    ],
                    'delete' => [
                        'template' => 'admin/SonataAdminBundle/CRUD/list__action_delete.html.twig',
                    ],
                ]
            ]);
    }
}
