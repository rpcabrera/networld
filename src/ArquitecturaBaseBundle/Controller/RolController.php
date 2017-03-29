<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Rol;
use ArquitecturaBaseBundle\Form\RolType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RolController extends BaseController
{
    /**
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function crearRolAction(Request $peticion){
        $em = $this->getEM();
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol,array(
            'action' => $this->generateUrl('crear_rol'),
            'method' => 'POST'
        ));
        $form->handleRequest($peticion);
        if ($form->isValid() && $form->isSubmitted()){
            $em->persist($rol);
            $em->flush();
            return $this->redirectToRoute('listar_rol');
        }
        return $this->render('@ArquitecturaBase/Rol/crear_rol.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarRolesAction(){
        return $this->render('@ArquitecturaBase/Rol/listar_roles.html.twig',array(

        ));
    }

    public function loadAjaxAction()
    {
        $em = $this->getEM();
        $roles = $em->getRepository('ArquitecturaBaseBundle:Rol')->findAll();
        $arrayResult = array();
        foreach ($roles as $rol){
            $rolPadre = $rol->getRolPadre();
            $rolPadreTag = '~';
            if (!is_null($rolPadre))
                $rolPadreTag = $rolPadre->getNombre();
            $arrayResult[] = array(
                'idrol' => $rol->getId(),
                'nombre' => $rol->getNombre(),
                'etiqueta' => $rol->getEtiqueta(),
                'descripcion' => $rol->getDescripcion(),
                'rolpadre' => $rolPadreTag,
                'concesiones' => $rol->getConcesiones()->count(),
                'activo' => $rol->getActivo(),
            );
        }
        return new JsonResponse(array(
            'roles' => $arrayResult
        ));
    }

    /**
     * @return JsonResponse
     */
    public function buscarRolesHijosDeRolAction(){
        $ga = $this->get('administracion.generador_arbol');
        $codificacionArbol = $ga->generarArbolRoles();
        return new JsonResponse($codificacionArbol);
    }

    /**
     * @param Request $peticion
     * @return JsonResponse
     */
    public function informacionNodoRolAction(Request $peticion){
        $em = $this->getDoctrine();
        $idNodo = $peticion->get('idNodo');
        $rol = $em->getRepository('ArquitecturaBaseBundle:Rol')->find($idNodo);
        return new JsonResponse(array(
            'nombre' => $rol->getNombre(),
            'etiqueta' => $rol->getEtiqueta(),
            'descripcion' => $rol->getDescripcion(),
            'activo' => $rol->getActivo(),
            'rolpadre' => $rol->getRolPadre() == null ? '-' : $rol->getRolPadre()->getNombre(),
            'usuarios' => $rol->getUsuarios()->toArray(),
            'concesiones' => $rol->getConcesiones()->toArray()
        ));
    }
}
