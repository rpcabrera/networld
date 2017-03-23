<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 8/09/16
 * Time: 14:08
 */

namespace ArquitecturaBaseBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ConcesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('menu', EntityType::class, array(
                'class' => 'ArquitecturaBaseBundle\Entity\Menu',
                'choice_label' => 'etiqueta',
                'empty_data'  => null,
                'placeholder' => 'Seleccione...',
                'required' => false,
            ))
            ->add('rol', EntityType::class, array(
                'class' => 'ArquitecturaBaseBundle\Entity\Rol',
                'choice_label' => 'etiqueta',
                'required' => false,
                'empty_data'  => null,
                'placeholder' => 'Seleccione...'
            ))
            ->add('activa', CheckboxType::class,array(
                'required' => false,
                'label' => 'Descripción'
            ))
            ->add('fechainicio', TextType::class,array(
                'required' => false
            ))
            ->add('fechafin', TextType::class,array(
                'required' => false
            ))
            ->add('aceptar', SubmitType::class,array(
                'label' => 'Aceptar'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArquitecturaBaseBundle\Entity\Concesion'
        ));
    }

}