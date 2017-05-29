<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\ShippingAddressFormType;
use AppBundle\Form\ShippingMethodFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends Controller
{
    /**
     * @Route("/checkout/billing",name="billing-address")
     *
     */
    public function billingAddressAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        $billingAddress = new BillingAddress();
        $billingAddress->setUser($user);
        $billingAddress->setFirstName($user->getFirstName());
        $billingAddress->setLastName($user->getLastName());
        $billingAddress->setEmailAddress($user->getUserName());
        $form = $this->createForm(BillingAddressFormType::class, $billingAddress);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $billingAddress = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($billingAddress);
            $em->flush();

            return $this->redirectToRoute('shipping-address');
        }

        return $this->render(':partials:checkout-billing.htm.twig', [
            'billingAddressForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/shipping",name="shipping-address")
     *
     */
    public function shippingAddressAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setUser($user);
        $shippingAddress->setFirstName($user->getFirstName());
        $shippingAddress->setLastName($user->getLastName());
        $shippingAddress->setEmailAddress($user->getUserName());

        $form = $this->createForm(ShippingAddressFormType::class, $shippingAddress);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $shippingAddress = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($shippingAddress);
            $em->flush();

            return $this->redirectToRoute('shipping-method');
        }

        return $this->render(':partials:checkout-shipping-address.htm.twig', [
            'shippingAddressForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/shipping-method",name="shipping-method")
     *
     */
    public function shippingMethodAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setUser($user);

        $form = $this->createForm(ShippingMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('shipping-method');
        }

        return $this->render(':partials:checkout-shipping-method.htm.twig', [
            'shippingMethodForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/payment",name="payment")
     *
     */
    public function paymentAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        $form = $this->createForm(ShippingAddressFormType::class);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('shipping-method');
        }

        return $this->render(':partials:checkout-pay.htm.twig', [
            'paymentForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }
}
