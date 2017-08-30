<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentMethod',null,[
                'placeholder'=>'Select a Payment Method'
            ])
            ->add('methodClientKey',null,[
                    'label'=>'Client Key'

            ])
            ->add('methodServerKey',TextType::class,[
                'required'=>false,
                'label'=>'Server Key'
            ])
            ->add('methodProductionId',null,[
                'label'=>'Production ID'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\PayOptions'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_pay_option_type';
    }
}
