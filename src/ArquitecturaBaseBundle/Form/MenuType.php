<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 27/09/16
 * Time: 9:55
 */

namespace ArquitecturaBaseBundle\Form;


use ArquitecturaBaseBundle\Entity\Icono;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etiqueta', TextType::class,array(
                'required' => false,
                'label' => 'Nombre del menu'
            ))
            ->add('ruta', ChoiceType::class, array(
                'choices' => $options['rutas'],
                'choice_label' => function($valor) {
                    return $valor;
                },
                'required' => false,
                'label' => 'Ruta'
            ))
            ->add('activo', CheckboxType::class,array(
                'required' => false
            ))
            ->add('padre', EntityType::class, array(
                'class' => 'ArquitecturaBaseBundle\Entity\Menu',
                'query_builder' => $options['menus_contenedores'],
                'placeholder' => '--Seleccione--',
                'choice_label' => 'etiqueta',
                'required' => false
            ))
            ->add('icono', HiddenType::class, array(
                'data' => '',
                'mapped' => true
            ))
            ->add('aceptar', SubmitType::class,array(
                'label' => 'Aceptar'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArquitecturaBaseBundle\Entity\Menu',
            'rutas' => array(),
            'menus_contenedores' => array()
        ));
    }

}