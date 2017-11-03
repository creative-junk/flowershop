<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season', Filters\ChoiceFilterType::class, [
                    'choices' => array(
                        'All Seasons' => 'All Seasons',
                        'Winter' => 'Winter',
                        'Spring' => 'Spring',
                        'Summer' => 'Summer',
                        'Fall' => 'Fall',
                    ),
                    'placeholder' => 'Choose a Season'
             ])
            ->add('min', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                    '70' => '70',
                    '80' => '80',
                    '90' => '90',
                    '100' => '100',
                    '200' => '200',
                    '300' => '300',
                    '400' => '400',
                    '500' => '500',
                    '600' => '600',
                    '700' => '700',
                    '800' => '800',
                    '900' => '900',
                    '1000' => '1000',
                    '2000' => '2000',
                    '3000' => '3000',
                    '4000' => '4000',
                    '5000' => '5000',
                    '6000' => '6000',
                ),
                'placeholder' => 'Min',
                'label'=>'Price'

            ])
            ->add('max', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                    '70' => '70',
                    '80' => '80',
                    '90' => '90',
                    '100' => '100',
                    '200' => '200',
                    '300' => '300',
                    '400' => '400',
                    '500' => '500',
                    '600' => '600',
                    '700' => '700',
                    '800' => '800',
                    '900' => '900',
                    '1000' => '1000',
                    '2000' => '2000',
                    '3000' => '3000',
                    '4000' => '4000',
                    '5000' => '5000',
                    '6000' => '6000',
                ),
                'placeholder' => 'Max',
                'label'=>false
            ])
            ->add('primaryColor',null,[
                'label'=>'Color',
                'required'=>false
            ])
            ->add('vaselifeFrom', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>'Vaselife',
                'placeholder' => 'Min',

            ])
            ->add('vaselifeTo', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>false,
                'placeholder' => 'Max',


            ])
            ->add('stemLengthFrom', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>'Stem Length',
                'placeholder' => 'Min',

            ])
            ->add('stemLengthTo', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>false,
                'placeholder' => 'Max',

            ])
            ->add('headsizeFrom', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>'Headsize',
                'placeholder' => 'Min',

            ])
            ->add('headsizeTo', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>false,
                'placeholder' => 'Max',

            ])
            ->add('numberOfHeadsFrom', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>'No. of Heads',
                'placeholder' => 'Min',

            ])
            ->add('numberOfHeadsTo', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '20' => '20',
                    '30' => '30',
                    '40' => '40',
                    '50' => '50',
                    '60' => '60',
                ),
                'required' => false,
                'label'=>false,
                'placeholder' => 'Max',

            ])
            ->add('country',CountryType::class,[
                'required'=>false,
                'placeholder'=>'Choose a Country'
            ])
            ->add('isScented',ChoiceType::class, [
                'choices'  => array(
                    'Yes' => true,
                    'No' => false,
                ),
                'label'=>'Scented',
                'expanded'=>true,
                'data'=>false
            ])
             ->add('isOnSale',ChoiceType::class, [
                'choices'  => array(
                    'Yes' => true,
                    'No' => false,
                ),
                 'choices_as_values' => true,
                 'expanded'=>true,
                 'label'=>'On Sale',
                 'data'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          //  'growers'=>null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product_filter';
    }
}
