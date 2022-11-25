<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_home']);
        $menu->addChild('Клиенты', ['route' => 'app_client_index']);
        $menu->addChild('Места', ['route' => 'app_place_index']);
        $menu->addChild('Заказы', ['route' => 'app_rent_index']);

        return $menu;
    }
}