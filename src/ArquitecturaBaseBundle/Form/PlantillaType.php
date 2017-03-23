<?php

namespace ArquitecturaBaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PlantillaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                'required' => false
            ))
            ->add('descripcion',TextareaType::class, array(
                'required' => false
            ))
            ->add('activo',CheckboxType::class, array(
                'required' => false
            ))
            ->add('contenido', TextareaType::class,array(
                'required' => false
            ))
            ->add('save',SubmitType::class, array(
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
            'data_class' => 'ArquitecturaBaseBundle\Entity\Plantilla'
        ));
    }
}
