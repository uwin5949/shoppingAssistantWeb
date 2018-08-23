<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 11:16 AM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShopType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Name'))
            ->add('lat', TextType::class, array('required' => true, 'label' => 'Latitude'))
            ->add('lon', TextType::class, array('required' => true, 'label' => 'Longitude'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }

}