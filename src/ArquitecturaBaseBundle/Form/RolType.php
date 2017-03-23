<?php

namespace ArquitecturaBaseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                'required' => false,
                'label' => 'Nombre del rol'
            ))
            ->add('etiqueta', TextType::class,array(
                'required' => false,
                'label' => 'Nombre de presentación'
            ))
            ->add('descripcion', TextareaType::class,array(
                'required' => false,
                'label' => 'Descripción'
            ))
            ->add('activo', CheckboxType::class,array(
                'required' => false
            ))
            ->add('rolPadre', EntityType::class, array(
                'class' => 'ArquitecturaBaseBundle\Entity\Rol',
                'choice_label' => 'nombre',
                'required' => false
            ))
            ->add('aceptar', SubmitType::class,array(
                'label' => 'Aceptar'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArquitecturaBaseBundle\Entity\Rol'
        ));
    }
}
