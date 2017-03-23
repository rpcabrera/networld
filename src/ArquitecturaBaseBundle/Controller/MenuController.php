<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Routing\Route;

class MenuController extends Controller implements ContainerAwareInterface
{

    public function generarMenuAction()
    {
        $menu = array();
        $usuario = $this->getUser();
        if (! ($usuario instanceof Usuario)){
            $concesiones = array();
            return $this->render('ArquitecturaBaseBundle:Menu:generar_menu.html.twig', array(
                'concesiones' => $concesiones
            ));
        }

        $em = $this->getDoctrine();
        $concesiones = $em->getRepository('ArquitecturaBaseBundle:Concesion')
                          ->buscarConcesionesDeUsuario($usuario);
        return $this->render('ArquitecturaBaseBundle:Menu:generar_menu.html.twig', array(
            'concesiones' => $concesiones
        ));
    }

    public function listarMenusAction(){
        $menus = $this->getDoctrine()
            ->getRepository('ArquitecturaBaseBundle:Menu')
            ->listarMenus();

    }


    public function testAction(){
        //Conocer cuales son las rutas y la cantidad de variables
        $router = $this->get('router.default');
        $rutas = $router->getRouteCollection();
        foreach ($rutas as $ruta) {
            if ($ruta instanceof Route)
            $pathVariables = $ruta->compile()->getPathVariables();
            ld(array(
                'Ruta' => $ruta->getPath(),
                'Variables' => $pathVariables
            ));
        }
        ldd();
    }

}
