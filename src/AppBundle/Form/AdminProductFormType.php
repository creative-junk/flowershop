<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('summary')
            ->add('description')
            ->add('imageFile', FileType::class)
            ->add('currency',ChoiceType::class,array(
                'choices' => array(
                    'US Dollars - $'=>'$',
                    'Kenya Shillings - Ksh'=>'Ksh',
                    'Euros - Eur'=>'Eur',
                ),
            ))
            ->add('price',NumberType::class)
            ->add('category',null,[
                'placeholder'=>'Choose a Category'
            ])
            ->add('isFeatured', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,

                ]
            ])
            ->add('isAuthorized',ChoiceType::class,[
                'choices'=>[
                    'Yes'=>true,
                    'No'=>false,
                ]
            ])

            ->add('isActive',ChoiceType::class,[
                'choices'=>[
                    'Yes'=>true,
                    'No'=>false,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Product'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_admin_product_form_type';
    }
}
