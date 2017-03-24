<?php

namespace ArquitecturaBaseBundle\Twig;

class UtilTwigExtension extends \Twig_Extension {

    private $kernel;

    public function __construct($kernel) {
        $this->kernel = $kernel;
    }

    public function getKernel() {
        return $this->kernel;
    }

    public function getName() {
        return "util_twig_extension";
    }

    public function getFunctions() {

        return array(
            'miga' => new \Twig_Function_Method($this, 'crearMiga', array('is_safe' => array('html'))),
        );
    }

    public function crearMiga($miga) {
        $str = '';
        $length_miga = count($miga);

        if (is_null($miga)) {
            $miga = array();
        }

        foreach ($miga as $key => $item) {
            if ($length_miga > 1) {
                $str .= '<li ><a href="' . $item['ruta'] . '">' . $item['etiqueta'] . '</a></li>';
                $length_miga--;
            } else {
                $str .= '<li>'.$item['etiqueta'].'</li>';
            }
        }
        return $str;
    }

}
