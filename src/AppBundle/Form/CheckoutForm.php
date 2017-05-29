<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'First Name *'
                ]
            ])
            ->add('lastName',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Last Name *'
                ]
            ])
            ->add('emailAddress',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Email Address *'
                ]
            ])
            ->add('company',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Company (Optional)'
                ]
            ])
            ->add('streetAddress',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Street Address *'
                ]
                ])
            ->add('town',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Town *'
                ]
            ])
            ->add('zip',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Zip *'
                ]
            ])
            ->add('country', CountryType::class,[
                'label' => false,
                'placeholder' => 'Select a Country *'

            ])
            ->add('phoneNumber',null,[
                'label' => false,
                'attr'=>[
                    'placeholder' => 'Phone Number *'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_checkout_form';
    }
}
