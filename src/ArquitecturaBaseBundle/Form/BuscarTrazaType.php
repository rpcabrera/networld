<?php

namespace ArquitecturaBaseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BuscarTrazaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('fecha', DateTimeType::class, array(
               'class' => 'date-picker',
               'required' => false))
            ->add('usuario', EntityType::class, array(
                'required' => false,
                'label' => 'Usuario'
            ))
            ->add('ip', TextType::class, array(
                'required' => false,
                'label' => 'IP'
            ))
            ->add('mac', TextType::class, array(
                'required' => false,
                'label' =>'MAC'
            ))
            ->add('accion', TextareaType::class, array(
                'class' => 'autosize-transition',
                'required' => false,
                'label' => 'Acción'
            ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArquitecturaBaseBundle\Entity\Visual\BuscarTraza'
        ));
    }
}
