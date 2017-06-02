<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuyerAgentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('agent',EntityType::class,[
            'class'=>'AppBundle\Entity\User',
            'choices'=>$options['agents'],
            'placeholder'=>'Select an Agent'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'agents'=>null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_buyer_agent_form_type';
    }
}
