<?php

namespace AppBundle\Form;

use AppBundle\Entity\Airline;
use AppBundle\Entity\Airport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingModeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('airline',EntityType::class,[
                'class'=>Airline::class,
                'choice_label'=>'airlineName',
                'label'=>false,
                'placeholder'=>'Select Airline'
            ])
            ->add('airport',EntityType::class,[
                'class'=>Airport::class,
                'choice_label'=>'airportName',
                'label'=>false,
                'placeholder'=>'Select Airport'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_shipping_mode_form';
    }
}
