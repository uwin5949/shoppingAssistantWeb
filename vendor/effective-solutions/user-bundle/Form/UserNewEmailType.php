<?php

namespace EffectiveSolutions\UserBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserNewEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('temporaryEmail', EmailType::class, array(
                'label'=>'New Email'
            ))
            ->add('save', SubmitType::class,array(
                'label'=>'Confirm'
            ))
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class'=>User::class
        ));

    }

    public function getBlockPrefix()
    {
        return 'effective_solutions_user_bundle_user_new_email_type';
    }
}
