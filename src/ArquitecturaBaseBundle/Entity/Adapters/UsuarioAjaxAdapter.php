<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 19/10/16
 * Time: 12:20
 */

namespace ArquitecturaBaseBundle\Entity\Adapters;


use ArquitecturaBaseBundle\Entity\Usuario;

class UsuarioAjaxAdapter
{
    /**
     * @var Usuario
     */
    private $usuario;

    /**
     * UsuarioAjaxAdapter constructor.
     * @param Usuario $usuario
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }


    public function arrayUser(){
        $u = $this->usuario;
        return array(
            'id' => $u->getId(),
            'nombre' => $u->getNombre(),
            'activo' => $u->getActivo()? "Si":"No",
            'descripcion' => $u->getDescripcion(),
            'correo' => $u->getCorreo()
        );
    }
}