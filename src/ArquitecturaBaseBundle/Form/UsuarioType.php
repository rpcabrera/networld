<?php

namespace ArquitecturaBaseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                'required' => true
            ))
            ->add('password', PasswordType::class,array(
                'required' => true
            ))
            ->add('roles',EntityType::class,array(
                'class' => 'ArquitecturaBaseBundle\Entity\Rol',
                'choice_label' => 'nombre',
                'multiple' => true,
                'expanded' => false,
                'required' => true
            ))
            ->add('activo',CheckboxType::class, array(
                'data' => true,
                'required' => false
            ))
            ->add('descripcion', TextareaType::class,array(
                'required' => false
            ))
            ->add('correo', EmailType::class,array(
                'required' => true
            ))
            ->add('foto',FileType::class, array(
                'required' => true,
                'label' => 'Imagen de perfil'
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
            'data_class' => 'ArquitecturaBaseBundle\Entity\Usuario'
        ));
    }
}
