<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 7/10/16
 * Time: 9:50
 */

namespace ArquitecturaBaseBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'glyphicon-inbox' => 'glyphicon-inbox'
            )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}