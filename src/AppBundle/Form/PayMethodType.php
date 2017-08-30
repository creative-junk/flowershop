<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('methodName')
            ->add('methodLogo',ProductImageForm::class)
            ->add('methodURL');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\PaymentMethod'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_pay_method_type';
    }
}
