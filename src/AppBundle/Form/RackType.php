<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 11:00 AM
 */

namespace AppBundle\Form;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RackType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rackNo', TextType::class, array('required' => true, 'label' => 'Rack No'))
            ->add('x_coord', TextType::class, array('required' => true, 'label' => 'X Coordinate'))
            ->add('y_coord', TextType::class, array('required' => true, 'label' => 'Y Coordinate'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }


}