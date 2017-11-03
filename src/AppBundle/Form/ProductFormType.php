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
            ->add('flowerType',null,[
                'label'=>'Flower Type',
                'placeholder' => 'Please Select Flower Type'
            ])
            ->add('title',null,[
                'label'=>'Flower Name'
            ])
            ->add('description')
            ->add('mainImage',ProductImageForm::class)
            ->add('openHeadTop',ProductImageForm::class)
            ->add('openHeadSide',ProductImageForm::class)
            ->add('closedHeadSide',ProductImageForm::class)
            ->add('openHeadBouquet',ProductImageForm::class)
            ->add('closedHeadBouquet',ProductImageForm::class)
            ->add('vaselife', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Number of Days']
            ])
            ->add('stemLength', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'CM']
            ])
            ->add('numberOfHeads', NumberType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Number of Heads']
            ])
            ->add('headsize', NumberType::class, [
                'label'=>'Headsize/Petal Length',
                'required' => false,
                'attr' => ['placeholder' => 'Inches']
            ])
            ->add('primaryColor')
            ->add('secondaryColor')
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
        ->add('isScented',ChoiceType::class, [
            'choices'  => array(
                 'Yes' => true,
                'No' => false,
            ),
            'placeholder' => 'Please Select'
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
