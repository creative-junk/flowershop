<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentBuyerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('buyer',EntityType::class,[
            'class'=>'AppBundle\Entity\Company',
            'choices'=>$options['agents'],
            'placeholder'=>'Select a Buyer'
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
