<?php

namespace AppBundle\Form;

use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AirportForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('airportName',null,[
                'attr'=>[
                    'placeholder'=>'e.g City Name- Airport Name'
                ]
            ])
            ->add('airportCode');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Airport'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_airport_form';
    }
}
