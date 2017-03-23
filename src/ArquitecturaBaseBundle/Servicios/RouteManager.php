<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 7/09/16
 * Time: 10:48
 */

namespace ArquitecturaBaseBundle\Servicios;


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;

class RouteManager
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RouteManager constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    /**
     * Devuelve las rutas establecidas para ser mostradas en el menu.
     * Esta debe estar marcada con el requerimiento de menu
     * @return array
     */
    public function listarRutasHabiles(){
        $rutas = $this->router->getRouteCollection();

        $arrayRutas = array();
        foreach ($rutas as $key => $ruta) {
            if ($ruta instanceof Route){
                if ($ruta->hasDefault('etiqueta') && $ruta->hasDefault('mostrarEnMenu') ){
                    $valorCondicion = $ruta->getDefault('mostrarEnMenu');
                    if (is_bool($valorCondicion))
                        if ($valorCondicion) $arrayRutas[] = $key;
                }
            }
        }
        return $arrayRutas;
    }
}