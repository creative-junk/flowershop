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
    public function mainAgentMenu(FactoryInterface $factory,array $options){

        $menu = $factory->createItem('root');

        $menu->addChild('Home',array('route'=>'agent_dashboard'));

      /*  $menu->addChild('Direct Market',array('route'=>'my_assigned_product_list'));
        $menu['Direct Market']->addChild('Assigned Products',array('route'=>'my_assigned_product_list'));
        $menu['Direct Market']->addChild('My WishList',array('route'=>'my-agent-wishlist'));
*/

        $menu->addChild('Auction Market',array('route'=>'agent_auction_product_list'));
        $menu['Auction Market']->addChild('Assigned Products',array('route'=>'my_assigned_product_list'));
        $menu['Auction Market']->addChild('My WishList',array('route'=>'my-agent-auction-wishlist'));

        $menu->addChild('Buyers',array('route'=>'agent_buyer_list'));
        $menu['Buyers']->addChild('My Buyers',array('route'=>'my-agent-buyers'));

        $menu->addChild('Growers',array('route'=>'agent_growers_list'));
        $menu['Growers']->addChild('My Growers',array('route'=>'my-agent-growers'));

        $menu->addChild('Orders',array('route'=>'my_agent_order_list'));
        $menu['Orders']->addChild('Assigned Orders',array('route'=>'my_agent_assigned_order_list'));
        $menu['Orders']->addChild('Received Orders',array('route'=>'my_agent_received_order_list'));
        $menu['Orders']->addChild('My Orders',array('route'=>'my_agent_order_list'));

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