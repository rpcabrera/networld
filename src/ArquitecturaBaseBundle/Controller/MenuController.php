<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Usuario;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Routing\Route;
use ArquitecturaBaseBundle\Entity\Menu;
use ArquitecturaBaseBundle\Form\MenuType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends BaseController implements ContainerAwareInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarMenusAction(){

        return $this->render('ArquitecturaBaseBundle:Menu:listar_menus.html.twig');
    }

    /**
     * @return JsonResponse
     */
    public function cargarMenusAjaxAction(){
        $em = $this->getDoctrine();
        $menus = $em->getRepository('ArquitecturaBaseBundle:Menu')->findAll();
        $menuArray = array();
        foreach ($menus as $menu) {
            if ($menu instanceof Menu){
                $menuPadre = $menu->getPadre() == null ? '~' : $menu->getPadre()->getEtiqueta();
                $menuArray[] = array(
                    'id' => $menu->getId(),
                    'etiqueta' => $menu->getEtiqueta(),
                    'ruta' => $menu->getRuta(),
                    'activo' => $menu->getActivo(),
                    'padre' => $menuPadre,
                    'submenus' => $menu->getElementos()->count(),
                    'concesiones' => $menu->getConcesiones()->count()
                );
            }
        }
        return new JsonResponse(array(
            'data' => $menuArray
        ));
    }

    /**
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function crearMenuAction(Request $peticion){
        $rm = $this->get('rutas.gestor');
        $rutas = $rm->listarRutasHabiles();
        $rutas = $this->getAdministracionGtr()->DescartarRutas($rutas);
        $qb_menus_contenedores = $this->getAdministracionGtr()->qb_MenusContenedores();
        $menu = new Menu();

        $form = $this->createForm(MenuType::class, $menu, array(
            'action' => $this->generateUrl('crear_menu'),
            'method' => 'POST',
            'rutas' => $rutas,
            'menus_contenedores' => $qb_menus_contenedores
        ));

        if ($peticion->isMethod("POST")){
            $form->handleRequest($peticion);
            // TODO Validar el menu
            $icono = $form->get('icono');
            $menu->setIcono($icono->getViewData());
            $this->getAdministracionGtr()->salvarMenu($menu);
            return $this->redirectToRoute('gestionar_menus');
        }else{
            return $this->render('ArquitecturaBaseBundle:Menu:crear_menu.html.twig',array(
                'form' => $form->createView()
            ));
        }
    }

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
