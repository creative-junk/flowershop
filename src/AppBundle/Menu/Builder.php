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

          $menu->addChild('Direct Market',array('route'=>'buyer-market'));
        //  $menu['Direct Market']->addChild('Wishlist',array('route'=>'my_buyer_wishlist'));

          $menu->addChild('Auction Market',array('route'=>'buyer_auction'));
          $menu['Auction Market']->addChild('Recommendations',array('route'=>'my_buyer_recommendations'));
         // $menu['Auction Market']->addChild('Wishlist',array('route'=>'my_buyer_auction_wishlist'));

         // $menu->addChild('Roses',array('route'=>'buyer_shop'));
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

        $menu->addChild('My Products',array('route'=>'my_agent_auction_list'));
        $menu['My Products']->addChild('Assigned Products',array('route'=>'agent_assigned_auction_list'));
        $menu['My Products']->addChild('Accepted Products',array('route'=>'agent_accepted_auction_list'));
        $menu['My Products']->addChild('Shipped Products',array('route'=>'agent_shipped_auction_list'));
        $menu['My Products']->addChild('My Stock',array('route'=>'agent_active_auction_list'));
        //$menu['Auction Market']->addChild('My WishList',array('route'=>'my-agent-auction-wishlist'));

        $menu->addChild('Buyers',array('route'=>'agent_buyer_list'));
        $menu['Buyers']->addChild('My Buyers',array('route'=>'my-agent-buyers'));

        $menu->addChild('Growers',array('route'=>'agent_growers_list'));
        $menu['Growers']->addChild('My Growers',array('route'=>'my-agent-growers'));

        $menu->addChild('Orders',array('route'=>'my_agent_order_list'));
        $menu['Orders']->addChild('Assigned Orders',array('route'=>'my_agent_assigned_order_list'));
        $menu['Orders']->addChild('Received Orders',array('route'=>'agent_order_list'));
        $menu['Orders']->addChild('My Orders',array('route'=>'my_agent_order_list'));

        return $menu;
    }
    public function mainGrowerMenu(FactoryInterface $factory,array $options){

        $menu = $factory->createItem('root');

        $menu->addChild('Home',array('route'=>'grower_dashboard'));


        $menu->addChild('My Products',array('route'=>'my_grower_roses'));
        $menu['My Products']->addChild('My Roses',array('route'=>'my_grower_roses'));
        $menu['My Products']->addChild('My Direct Market',array('route'=>'my_grower_direct_market'));
        $menu['My Products']->addChild('My Auction Market',array('route'=>'my_grower_auction_list'));

        $menu->addChild('My Auction',array('route'=>'my_grower_auction_list'));
        $menu['My Auction']->addChild('Unassigned',array('route'=>'unassigned_auction_list'));
        $menu['My Auction']->addChild('Pending Agent',array('route'=>'pending_auction_list'));
        $menu['My Auction']->addChild('Accepted By Agent',array('route'=>'accepted_auction_list'));
        $menu['My Auction']->addChild('Shipped',array('route'=>'shipped_auction_list'));
        $menu['My Auction']->addChild('Active',array('route'=>'active_auction_list'));

        $menu->addChild('Seedlings',array('route'=>'grower_seedlings_list'));
       // $menu['Seedlings']->addChild('Wishlist',array('route'=>'my-seedling-wishlist'));


        $menu->addChild('Buyers',array('route'=>'grower_buyer_list'));
        $menu['Buyers']->addChild('My Buyers',array('route'=>'my_grower_buyer_list'));

        $menu->addChild('Breeders',array('route'=>'breeder_list'));
        $menu['Breeders']->addChild('My Breeders',array('route'=>'my_breeder_list'));

        $menu->addChild('Agents',array('route'=>'grower_agent_list'));
        $menu['Agents']->addChild('My Agents',array('route'=>'my_grower_agent_list'));


         return $menu;
    }
    public function mainBreederMenu(FactoryInterface $factory,array $options){

        $menu = $factory->createItem('root');

        $menu->addChild('Home',array('route'=>'breeder_dashboard'));
        $menu->addChild('My Seedlings',array('route'=>'my_breeder_seedling_list'));

        $menu->addChild('My Market',array('route'=>'my_breeder_direct_list'));

        $menu->addChild('Growers',array('route'=>'breeder_growers_list'));
        $menu['Growers']->addChild('My Growers',array('route'=>'my_breeder_growers'));

        $menu->addChild('Orders',array('route'=>'breeder_order_list'));

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