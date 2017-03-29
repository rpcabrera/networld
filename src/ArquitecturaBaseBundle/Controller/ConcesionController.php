<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Adapters\UsuarioAjaxAdapter;
use ArquitecturaBaseBundle\Entity\Concesion;
use ArquitecturaBaseBundle\Entity\Usuario;
use ArquitecturaBaseBundle\Form\ConcesionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ConcesionController extends BaseController
{
    /**
     * @param Request $peticion
     * @param $idmenu
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function crearConcesionAction(Request $peticion, $idmenu){
        //Si no se ingresa un idmenu valido, redirigir a la interfaz de gestion del menu
        if ($idmenu == -1) return $this->redirectToRoute('listar_concesiones');

        $em = $this->getDoctrine();

        $concesion = new Concesion();
        $menu = $em->getRepository('ArquitecturaBaseBundle:Menu')->find($idmenu);
        $concesion->setMenu($menu);

        $form = $this->createForm(ConcesionType::class,$concesion,array(
            'action' => $this->generateUrl('crear_concesion',array('idmenu' => $idmenu)),
            'method' => 'POST'
        ));

        $form->handleRequest($peticion);
        if ($form->isSubmitted() && $form->isValid()){
            $fechaInicio = \DateTime::createFromFormat("m/d/Y",$concesion->getFechainicio());
            $fechaFin = \DateTime::createFromFormat("m/d/Y",$concesion->getFechafin());
            $concesion->setFechainicio($fechaInicio);
            $concesion->setFechafin($fechaFin);
            $concesion->setMenu($menu);
            $em->persist($concesion);
            $em->flush();
            return $this->redirectToRoute('listar_concesiones');
        }
        return $this->render('@ArquitecturaBase/Administracion/crear_concesion.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function gestionarConcesionesAction(){
        return $this->render('ArquitecturaBaseBundle:Concesiones:gestionar_concesiones_index.html.twig', array(
        ));
    }

    /**
     * @return JsonResponse
     */
    public function cargarUsuariosAjaxAction(){
        $usuarios = $this->getAdministracionGtr()->ListarUsuarios();
        $usuariosArray = array();
        foreach ($usuarios as $u) {
            /** @var Usuario $u */
            $adapter = new UsuarioAjaxAdapter($u);
            $usuariosArray[] = $adapter->arrayUser();
        }
        return new JsonResponse($usuariosArray);
    }

    /**
     * @param $idusuario
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarConcesionesDeUsuarioAction($idusuario){
        $concesiones = $this->getAdministracionGtr()
            ->listarConcesionesDeUsuario($idusuario);
        return $this->render('@ArquitecturaBase/Concesiones/concesiones_usuario.html.twig',array(

        ));
    }

    /**
     *
     */
    public function eliminarConcesionAction(){

    }
}
