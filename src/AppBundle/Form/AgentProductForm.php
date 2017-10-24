<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('availableStock', null, array(
                'attr' => ['readonly' => true],
            ))
            ->add('pricePerStem',NumberType::class,[
                'attr' => ['placeholder' => 'Price Per Stem ( FOB ) ']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\AuctionProduct'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_agent_product_form';
    }
}
