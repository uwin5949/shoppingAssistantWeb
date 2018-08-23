<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 12:04 PM
 */

namespace AppBundle\Form;


use AppBundle\Repository\ItemRepository;
use AppBundle\Repository\RackRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Name'))
            ->add('rack', EntityType::class, array(
                'required' => true,
                'label' => 'Rack',
                'class' => 'AppBundle:Rack',
                'choice_label' => 'rackNo',
                'query_builder' => function (RackRepository $rr) use ($options){
                    return $rr  ->createQueryBuilder('r')
                                ->where('r.shop =:shopId')
                                ->setParameter('shopId',$options['shopId']);
                }
            ))
            ->add('code', TextType::class, array('required' => true, 'label' => 'Item Code'))
            ->add('price', TextType::class, array('required' => true, 'label' => 'Price'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Item',
            'shopId' => 'AppBundle\Entity\Shop'
        ));
    }

}