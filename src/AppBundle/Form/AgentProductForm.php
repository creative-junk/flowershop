<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'attr' => ['readonly' => true],
            ))
            ->add('summary', null, array(
                'attr' => ['readonly' => true],
            ))
            ->add('description', null, array(
                'attr' => ['readonly' => true],
            ))
            ->add('imageFile', FileType::class, array(
                'attr' => ['readonly' => true],
            ))
            ->add('quantity', null, array(
                'label_format' => 'Quantity in Bundle',
                'attr' => ['readonly' => true],
            ))
            ->add('currency', ChoiceType::class, array(
                'choices' => array(
                    'US Dollars - $' => '$',
                    'Kenya Shillings - Ksh' => 'Ksh',
                    'Euros - Eur' => 'Eur',
                ),
                'attr' => ['readonly' => true],
            ))
            ->add('bundlePrice', null, array(
                'attr' => ['readonly' => true],
            ))
            ->add('finalPrice')
            ->add('agent', null, array(
                'attr' => ['readonly' => true],
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Auction'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_agent_product_form';
    }
}
