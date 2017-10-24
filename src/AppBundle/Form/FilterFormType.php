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
            ->add('color', Filters\ChoiceFilterType::class, [
                'choices' => array(
                    'Pink' => 'Pink',
                    'Red' => 'Red',
                    'Yellow' => 'Yellow',
                    'White' => 'White',
                    'Peach' => 'Peach',
                ),
                'placeholder' => 'Choose a Color',
                'data' => isset($options['data']) ? $options['data']['color'] : ''

            ])

            ->add('price',Filters\NumberFilterType::class)
            ->add('vaselife', Filters\NumberFilterType::class, [
                'required' => false,

            ])
            ->add('stemLength', Filters\NumberFilterType::class, [
                'required' => false,
            ])
            ->add('headsize', Filters\NumberFilterType::class, [
                'required' => false,
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
                'placeholder' => 'Please Select'
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
