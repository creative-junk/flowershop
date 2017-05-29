<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('user',Filters\EntityFilterType::class,[
                'class'=>'AppBundle\Entity\User',
                'placeholder' => 'Choose a Grower',
                'label' => 'Grower',
                'required'=>false
            ])
            ->add('vaselife', Filters\NumberFilterType::class, [
                'required' => false,

            ])
            ->add('stemLength', Filters\NumberFilterType::class, [
                'required' => false,
            ])
            ->add('headsize', Filters\NumberFilterType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'csrf_protection'=>false,
            'validation_groups' => array('filtering')
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product_filter';
    }
}
