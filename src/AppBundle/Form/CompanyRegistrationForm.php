<?php

namespace AppBundle\Form;

use AppBundle\Entity\Company;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName')
            ->add('email',EmailType::class)
            ->add('telephoneNumber')
            ->add('reference', ChoiceType::class, [
                'choices' => array(
                    'Google Search' => 'Google Search',
                    'Facebook' => 'Facebook',
                    'Twitter' => 'Twitter',
                ),
                'placeholder' => 'Please Select',
                'label'=>'How did you first hear about Iflora?'

            ])
            ->add('currency',ChoiceType::class,array(
                'choices' => array(
                    'US Dollars - $'=>'USD',
                    'Kenya Shillings - Ksh'=>'KES',
                    'Euros - Eur'=>'EUR',
                    'GBP Pounds' =>'GBP',
                    'Canadian Dollar' => 'CAD',
                    'Japanese Yen' => 'JPY'
                ),
                'placeholder' => 'Please Select',
            ))

            ->add('country', CountryType::class,[
                'placeholder' => 'Please Select',
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
        return 'app_bundle_company_registration_form';
    }
}
