<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class GrowerCompanyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName',null,[
                'disabled'=>true
            ])
            ->add('email',EmailType::class,[
                'disabled'=>true
            ])
            ->add('telephoneNumber')
            ->add('altitude')
            ->add('numberOfVarieties')
            ->add('aboutCompany',TextareaType::class)
            ->add('numberOfEmployees')
            ->add('website',null,[
                'attr'=>['placeholder'=>'http://www.iflora.biz']
            ])
            ->add('facebookPage',null,[
                'attr'=>['placeholder'=>'http://www.facebooc.com/iflora']
            ])
            ->add('twitterPage',null,[
                'attr'=>['placeholder'=>'http://www.twitter.com/iflora']
            ])
            ->add('instagramPage',null,[
                'attr'=>['placeholder'=>'http://www.instagram.com/iflora']
            ])
            ->add('country', CountryType::class,[
                'placeholder' => 'Please Select',
            ])
            ->add('gallery',AddLogoForm::class,[
                'label'=>false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Company'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_buyer_company_form';
    }
}
