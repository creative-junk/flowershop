<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProductImages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProductImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('imageFile',VichFileType::class,[
               'label'=>false,
               'allow_delete'=>false
           ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductImages::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_product_image_form';
    }
}
