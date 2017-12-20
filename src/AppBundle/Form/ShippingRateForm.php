<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingRateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('airport')
            ->add('airline')
            ->add('minimumCharge',null,[
                'label'=>'Minimum Charge (USD)'
            ])
            ->add('baseRate',null,[
                'label'=>'Normal Rate (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ])
            ->add('firstIncrement',null,[
                'label'=>'+45KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                    ]
            ])
            ->add('secondIncrement',null,[
                'label'=>'+100KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ])
            ->add('thirdIncrement',null,[
                'label'=>'+300KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ])
            ->add('fourthIncrement',null,[
                'label'=>'+500KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ])
            ->add('fifthIncrement',null,[
                'label'=>'+1000KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ])
            ->add('sixthIncrement',null,[
                'label'=>'+2000KG (USD)',
                'attr'=>[
                    'placeholder'=>'0.00'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ShippingRate'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_shipping_rate_form';
    }
}
