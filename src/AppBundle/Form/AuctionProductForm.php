<?php

namespace AppBundle\Form;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuctionProductForm extends AbstractType
{
    function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product',null,[
                'choices'=>$options['options']['roses'],
                'placeholder' => 'Select Rose'

            ])
            ->add('sellingAgent',null,[
                'choices'=>$options['options']['agents'],
                'placeholder' => 'Select Agent',
                'required'=> false
            ])
            ->add('numberOfStems',NumberType::class,[
                'attr' => ['placeholder' => 'Number of Stems']
            ])

            ->add('quality',null,[
                'attr' => ['placeholder' => 'Quality']
            ])
            ->add('pricePerStem',NumberType::class,[
                'attr' => ['placeholder' => 'Price Per Stem']
            ])
            ->add('announceToAgents');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Auction',
            'options'=>null
        ]);
    }

    public function getName()
    {
        return 'app_bundle_auction_product_form';
    }
}
