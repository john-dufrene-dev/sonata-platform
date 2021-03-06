<?php

namespace App\Service\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function header(array $options)
    {
        // {{ knp_menu_render('header') }}

        $menu = $this->factory->createItem('header', [
            'childrenAttributes'    => [
                'id'        => 'header_menu',
                'class'     => 'header_menu',
            ],
        ]);

        $menu->addChild('Account', [
            'route' => 'account_index'

        ])->setAttribute('class', 'header_menu_children');

        return $menu;
    }

    public function footer(array $options)
    {
        // {% set menu = knp_menu_get('footer', [], {include_accountpage: true}) %}
        // {{ knp_menu_render(menu) }}

        $menu = $this->factory->createItem('footer', [
            'childrenAttributes'    => [
                'id'        => 'footer_menu',
                'class'     => 'footer_menu',
            ],
        ]);

        if (isset($options['include_accountpage']) && $options['include_accountpage']) {
            $menu->addChild('Account', [
                'route' => 'account_index'

            ])->setAttribute('class', 'footer_menu_children');
        }

        // ... add more children

        return $menu;
    }
}
