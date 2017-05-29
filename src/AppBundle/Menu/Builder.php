<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/19/2017
 * Time: 5:02 PM
 ********************************************************************************/

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Builder implements ContainerAwareInterface
{
      use ContainerAwareTrait;

      public function mainUserMenu(FactoryInterface $factory,array $options){

          $menu = $factory->createItem('root');

          $menu->addChild('Home',array('route'=>'home'));
          $menu->addChild('Direct Market',array('route'=>'buyer_shop'));
          $menu->addChild('Auction Market',array('route'=>'buyer_auction'));
          $menu->addChild('Roses',array('route'=>'buyer_shop'));
          $menu->addChild('Growers',array('route'=>'buyer_growers'));
          $menu->addChild('Agents',array('route'=>'buyer_agents'));

          return $menu;
      }
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        // TODO: Implement setContainer() method.
    }
}