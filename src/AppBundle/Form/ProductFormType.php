<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
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
            ->add('vaselife', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Number of Days']
            ])
            ->add('stemLength', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'CM']
            ])
            ->add('headsize', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Inches']
            ])
            ->add('quality')
            ->add('color', ChoiceType::class, [
                'choices' => array(
                    'Pink' => 'Pink',
                    'Red' => 'Red',
                    'Yellow' => 'Yellow',
                    'White' => 'White',
                    'Peach' => 'Peach',
                ),
                'placeholder' => 'Choose a Color'
            ])
            ->add('season', ChoiceType::class, [
                'choices' => array(
                    'All Seasons' => 'All Seasons',
                    'Winter' => 'Winter',
                    'Spring' => 'Spring',
                    'Summer' => 'Summer',
                    'Fall' => 'Fall',
                ),
                'placeholder' => 'Choose a Season'
            ])
            ->add('category',null,[
                'placeholder'=>'Choose a Category'
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
        return 'app_bundle_product_form_type';
    }
}
