<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('agents', Entity::class,array(
                'class'=>'AppBundle\Entity\BuyerAgent',
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('u');
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_agent_form_type';
    }
}
