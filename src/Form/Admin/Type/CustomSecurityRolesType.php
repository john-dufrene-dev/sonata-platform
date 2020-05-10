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

namespace App\Form\Admin\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nucleos\UserAdminBundle\Form\Transformer\RestoreRolesTransformer;

class CustomSecurityRolesType extends AbstractType
{
    /**
     * container
     *
     * @var mixed
     */
    protected $container;

    /**
     * unset_roles - ALL ROLES YOU WANT TO UNSET
     *
     * @var array
     */
    protected $unset_roles = [
        'ROLE_SONATA_MAINTENANCE_EDIT',
        'ROLE_SONATA_MAINTENANCE_CREATE',
        'ROLE_SONATA_MAINTENANCE_EXPORT',
        'ROLE_SONATA_MAINTENANCE_ALL',
        'ROLE_SONATA_MAINTENANCE_DELETE',
        'ROLE_SONATA_MAINTENANCE_VIEW',
        'ROLE_SONATA_REDIRECT_VIEW',
    ];

    /**
     * __construct
     *
     * @param  mixed $container
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * buildForm
     *
     * @param  mixed $formBuilder
     * @param  mixed $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $transformer = new RestoreRolesTransformer($this->container->get('custom.user.editable_role_builder'));

        // GET METHOD
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) use ($transformer): void {
            $transformer->setOriginalRoles($event->getData());
        });

        // POST METHOD
        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, static function (FormEvent $event) use ($transformer): void {
            $transformer->setOriginalRoles($event->getForm()->getData());
        });

        $formBuilder->addModelTransformer($transformer);
    }

    /**
     * buildView
     *
     * @param  mixed $view
     * @param  mixed $form
     * @param  mixed $options
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = $view->vars['attr'];

        if (isset($attr['class']) && empty($attr['class'])) {
            $attr['class'] = 'sonata-medium custom-security-roles-type';
        }

        $view->vars['choice_translation_domain'] = false; // RolesBuilder all ready does translate them

        $view->vars['attr'] = $attr;
        $view->vars['read_only_choices'] = $options['read_only_choices'];
    }

    /**
     * configureOptions
     *
     * @param  mixed $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // make expanded default value
            'expanded' => true,

            'choices' => function (Options $options, $parentChoices) {
                if (!empty($parentChoices)) {
                    return [];
                }
                $roles = $this->container->get('custom.user.editable_role_builder')->getRoles($options['choice_translation_domain'], $options['expanded']);

                foreach ($roles as $role) {
                    if (\in_array($role, $this->unset_roles)) {
                        unset($roles[$role]);
                    }
                }

                return array_flip($roles);
            },

            'read_only_choices' => function (Options $options) {
                if (!empty($options['choices'])) {
                    return [];
                }

                return $this->container->get('custom.user.editable_role_builder')->getRolesReadOnly($options['choice_translation_domain']);
            },

            'choice_translation_domain' => static function (Options $options, $value) {
                // if choice_translation_domain is true, then it's the same as translation_domain
                if (true === $value) {
                    $value = $options['translation_domain'];
                }
                if (null === $value) {
                    // no translation domain yet, try to ask sonata admin
                    $admin = null;
                    if (isset($options['sonata_admin'])) {
                        $admin = $options['sonata_admin'];
                    }
                    if (null === $admin && isset($options['sonata_field_description'])) {
                        $admin = $options['sonata_field_description']->getAdmin();
                    }
                    if (null !== $admin) {
                        // $value = $admin->getTranslationDomain(); // change translation domain
                        $value = 'roles';
                    }
                }

                return $value;
            },

            'data_class' => null,
        ]);
    }

    /**
     * getParent
     *
     * @return void
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * getBlockPrefix
     *
     * @return void
     */
    public function getBlockPrefix()
    {
        return 'user_security_roles';
    }
}
