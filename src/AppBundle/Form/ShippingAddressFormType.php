<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingAddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('emailAddress')
        ->add('streetAddress')
        ->add('town')
        ->add('zip')
        ->add('phoneNumber')
        ->add('country', CountryType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ShippingAddress'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_shipping_address_form_type';
    }
}
