<?php
/**
 * Created by PhpStorm.
 * User: Rigoberto
 * Date: 24/03/2017
 * Time: 11:37 AM
 */

namespace ArquitecturaBaseBundle\Servicios;


use ArquitecturaBaseBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;

class FileManager
{
    /**
     * @var string
     */
    private $directorio_raiz_fotos;

    /**
     * FileManager constructor.
     * @param string $directorio_raiz_fotos
     */
    public function __construct($directorio_raiz_fotos)
    {
        $this->directorio_raiz_fotos = $directorio_raiz_fotos;
    }

    /**
     * @param $usuario Usuario
     * @param $request Request
     * @return string
     */
    public function obtenerUrlFotoUsuario($usuario, $request){
        $nombreFoto = $usuario->getFoto();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $url = $baseurl."/".$this->directorio_raiz_fotos."/".$nombreFoto;
        return $url;
    }

}