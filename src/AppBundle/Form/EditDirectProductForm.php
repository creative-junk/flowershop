<?php

namespace AppBundle\Form;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditDirectProductForm extends AbstractType
{
    function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('numberOfStems',NumberType::class,[
                'attr' => ['placeholder' => 'Number of Stems']
            ])
            ->add('minimumOrder',NumberType::class,[
                'attr' => ['placeholder' => 'Minimum Order']
            ])
            ->add('stemsPerBox',NumberType::class,[
                'attr' => ['placeholder' => 'Stems Per Box']
            ])
            ->add('quality',null,[
                'attr' => ['placeholder' => 'Quality']
            ])
            ->add('pricePerStem',NumberType::class,[
                'attr' => ['placeholder' => 'Price Per Stem']
            ])
            ->add('announceToBuyers')
            ->add('isOnSale')
            ->add('previousPrice')
            ->add('areSamplesAvailable',null,[
                'label'=>'Are Samples Available ?'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Direct',
            'roses'=>null
        ]);
    }

    public function getName()
    {
        return 'app_bundle_auction_product_form';
    }
}
