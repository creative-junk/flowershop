<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingAddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('emailAddress')
            ->add('company')
            ->add('streetAddress')
            ->add('town')
            ->add('zip')
            ->add('phoneNumber')
            ->add('country', CountryType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\BillingAddress'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_billing_address_form_type';
    }
}
