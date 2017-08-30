<?php

namespace AppBundle\Form;

use AppBundle\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo',ProductImageForm::class)
            ->add('image1',ProductImageForm::class,[
                'required'=>false
            ])
            ->add('image2',ProductImageForm::class,[
                'required'=>false
            ])
            ->add('image3',ProductImageForm::class,[
                'required'=>false
            ])
            ->add('image4',ProductImageForm::class,[
                'required'=>false
            ])
            ->add('image5',ProductImageForm::class,[
                'required'=>false
            ]);;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Gallery::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_gallery_form';
    }
}
