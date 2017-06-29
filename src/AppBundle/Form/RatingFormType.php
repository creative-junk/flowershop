<?php

namespace AppBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quality',RatingType::class,[
                'label'=>'Quality'
            ])
            ->add('price',RatingType::class,[
                'label'=>'Price'
            ])
            ->add('value',RatingType::class,[
                'label'=>'Value'
            ])
            ->add('summary')
            ->add('review',TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Rating'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_rating_form_type';
    }
}
