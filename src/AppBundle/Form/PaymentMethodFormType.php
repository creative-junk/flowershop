<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PaymentMethodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile',VichFileType::class,[
            'required'=>true,
            'allow_delete'=>true,
            'label'=>false

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\AuctionOrder'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_payment_method_form_type';
    }
}
