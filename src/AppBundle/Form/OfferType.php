<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 1:18 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('percentage', TextType::class, array('required' => true, 'label' => 'Percentage'))
            ->add('description', TextType::class, array('required' => true, 'label' => 'Description'))
            ->add('items')
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }


}