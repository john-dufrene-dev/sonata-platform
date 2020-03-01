<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Entity\User\UserInfo;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserAdmin extends AbstractAdmin
{
    const API_ALL = 'ROLE__USER_API_ALL';
    const API_LIST = 'ROLE__USER_API_LIST';
    const API_CREATE = 'ROLE__USER_API_CREATE';
    const API_EDIT = 'ROLE__USER_API_EDIT';
    const API_DELETE = 'ROLE__USER_API_DELETE';

    public $supportsPreviewMode = true;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('roles')
            ->add('enabled')
            ->add('created_at')
            ->add('updated_at')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->add('email')
            ->add('enabled', null, ['editable' => true])
            ->add('created_at')
            ->add('updated_at')
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
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $description = 'Attention, vous ne pouvez pas éditer le profil tant que l\'utilisateur n\'existe pas !';

        // define group zoning
        $formMapper
            ->tab('Informations générales de l\'utilisateur')
                ->with('Informations générales de l\'utilisateur')->end()
            ->end()
            ->tab('Profil utilisateur')
                ->with('Profil utilisateur', ['description' => $description])->end()
            ->end()
            ->tab('Sécurité utilisateur')
                ->with('Activation / Désactivation de l\'utilisateur')->end()
                ->with('Api token de l\'utilisateur')->end()
            ->end()
        ;

        $formMapper
        ->tab('Informations générales de l\'utilisateur')
            ->with('Informations générales de l\'utilisateur', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-info',
                'description' => 'Mise à jour des informations générales',
            ])
                ->add('email')
                ->add('plainPassword', PasswordType::class, [
                    'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                ])
            ->end()
        ->end()
        ;
        
        if($this->getSubject() && null !== $this->getSubject()->getId()) {
            $formMapper
            ->tab('Profil utilisateur')
                ->with('Profil utilisateur', [
                    'class'       => 'col-md-12',
                    'box_class'   => 'box box-solid box-info',
                    'description' => 'Mise à jour du profil utilisateur',
                ])     
                ->add('infos.name')
                ->end()
            ->end()
            ;
        }
      
        $formMapper
        ->tab('Sécurité utilisateur')
            ->with('Activation / Désactivation de l\'utilisateur', [
                'class'       => 'col-md-6',
                'description' => 'Lorem ipsum',
            ])
                ->add('enabled', null, ['required' => false])
            ->end()
            ->with('Api token de l\'utilisateur', [
                'class'       => 'col-md-6',
                'description' => 'Lorem ipsum',
            ])
                ->add('apiToken', null, ['required' => false])
            ->end()
            ->with('Rôles attribués à l\'utilisateur', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-info',
                'description' => 'Lorem ipsum',
            ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'API_ALL' => self::API_ALL,
                        'API_LIST' => self::API_LIST,
                        'API_CREATE' => self::API_CREATE,
                        'API_EDIT' => self::API_EDIT,
                        'API_DELETE' => self::API_DELETE,
                    ],
                    'expanded'  => true,
                    'multiple'  => true,
                ])
            ->end()
        ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    { 
        $showMapper
        ->tab('General')
            ->with('General', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-info',
                'description' => 'Lorem ipsum',
            ])
                ->add('id')
                ->add('email')
                ->add('created_at')
                ->add('updated_at')
            ->end()
        ->end()

        ->tab('Profile')
            ->with('Profile', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-info',
                'description' => 'Lorem ipsum',
            ])
                ->add('infos.name')          
            ->end()
        ->end()

        ->tab('Security')
            ->with('Security', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-info',
                'description' => 'Lorem ipsum',
            ])
                ->add('enabled', null, ['editable' => true])
                ->add('roles')
                ->add('apiToken')       
            ->end()
        ->end()    
        ;
    }

    public function prePersist($object)
    {
        $infos = new UserInfo();

        parent::prePersist($object);
        $object->setInfos($infos);
        $this->updateUser($object);
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);
        $this->updateUser($object);

        $object->setUpdatedAt(new \DateTime());
    }

    //update password
    public function updateUser($object) 
    {  
        if (null != $object->getPlainPassword()) {

            $container = $this->getConfigurationPool()->getContainer();
            $encoder = $container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($object, $object->getPlainPassword());

            $object->setPassword($encoded);
        }
    } 
}
