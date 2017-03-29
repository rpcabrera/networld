<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Usuario;
use ArquitecturaBaseBundle\Form\UsuarioType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends BaseController
{
    /**
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function crearUsuarioAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();

        $usuario = new Usuario();

        $form = $this->createForm(UsuarioType::class,$usuario,array(
            'action' => $this->generateUrl('usuario_agregar'),
            'method' => 'POST'
        ));

        $form->handleRequest($peticion);

        $existe = $em->getRepository('ArquitecturaBaseBundle:Usuario')->findBy(array(
            'nombre' => $usuario->getNombre()
        ));
        if (count($existe) > 0){
            $error = new FormError("Existe un usuario con el mismo nombre introducido");
            $form->addError($error);
        }

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $fichero */
            $fichero = $usuario->getFoto();
            $nombreNuevo = $usuario->getNombre().".".$fichero->guessExtension();
            $urlFotos = $this->getParameter('url_fotos');
            try{
                $fichero->move(
                    $urlFotos,
                    $nombreNuevo
                );
                $usuario->setFoto($nombreNuevo);
                $pass = password_hash($usuario->getPassword(),PASSWORD_BCRYPT);
                $usuario->setPassword($pass);
                $em->persist($usuario);
                $em->flush();
                $mensaje = $this->get('translator')->trans('gestion_usuarios.mensajes.exito_adicion',array(),'arquitectura');
                $this->addFlash('success', $mensaje);
                return $this->redirectToRoute('usuario_listar');
            }catch(\Exception $e){
                $error = new FormError("Ha ocurrido un error en el servidor.");
                $form->addError($error);
                return $this->render('ArquitecturaBaseBundle:Administracion:crear_usuario.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $form->getErrors()
                ));
            }
        }
        return $this->render('ArquitecturaBaseBundle:Usuario:crear_usuario.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $peticion
     * @param $idusuario int
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modificarUsuarioAction(Request $peticion, $idusuario){
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('ArquitecturaBaseBundle:Usuario')->find($idusuario);
        $form = $this->createForm(UsuarioType::class,$usuario,array(
            'action' => $this->generateUrl('usuario_modificar', array('idusuario' => $idusuario)),
            'method' => 'POST'
        ));
        $form->handleRequest($peticion);
        $existe = $em->getRepository('ArquitecturaBaseBundle:Usuario')->findBy(array(
            'nombre' => $usuario->getNombre()
        ));
        if (count($existe) > 0){
            $error = new FormError("Existe un usuario con el mismo nombre introducido");
            $form->addError($error);
        }

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $fichero */
            $fichero = $usuario->getFoto();
            $nombreNuevo = $usuario->getNombre().".".$fichero->guessExtension();
            $urlFotos = $this->getParameter('url_fotos');
            try{
                $fichero->move(
                    $urlFotos,
                    $nombreNuevo
                );
                $usuario->setFoto($nombreNuevo);
                $pass = password_hash($usuario->getPassword(),PASSWORD_BCRYPT);
                $usuario->setPassword($pass);
                $em->persist($usuario);
                $em->flush();
                $mensaje = $this->get('translator')->trans('gestion_usuarios.mensajes.exito_adicion',array(),'arquitectura');
                $this->addFlash('success', $mensaje);
                return $this->redirectToRoute('usuario_listar');
            }catch(\Exception $e){
                $error = new FormError("Ha ocurrido un error en el servidor.");
                $form->addError($error);
                return $this->render('ArquitecturaBaseBundle:Administracion:crear_usuario.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $form->getErrors()
                ));
            }
        }
        return $this->render('ArquitecturaBaseBundle:Usuario:crear_usuario.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarUsuariosAction(){
        return $this->render('@ArquitecturaBase/Usuario/listar_usuarios.html.twig');
    }

    public function loadAjaxUsuariosAction(Request $request){
        $em = $this->getEM();
        $usuarioAutenticado = $this->getUser();
        $usuarios = $em->getRepository('ArquitecturaBaseBundle:Usuario')->listarExcepto($usuarioAutenticado->getId());
        $usuarios_array = array();
        foreach ($usuarios as $u){
            /** @var Usuario $u */
            $usuarios_array[] = array(
                'id' => $u->getId(),
                'nombre' => $u->getNombre(),
                'roles' => implode(', ',$u->getRoles()),
                'estado' => $u->getActivo(),
                'correo' => $u->getCorreo(),
                'foto' => $this->get('file_maganer')->obtenerUrlFotoUsuario($u, $request)
            );
        }
        return new JsonResponse(array(
            'data' => $usuarios_array
        ));
    }

    public function eliminarUsuarioAction(Request $peticion){
        $idusuario = $peticion->get('idusuario');
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('ArquitecturaBaseBundle:Usuario')->find($idusuario);
        try{
            $em->remove($usuario);
            $em->flush();
            return new JsonResponse(array(
                'type' => 'success',
                'message' => $this->get('translator')->trans(
                    'gestion_usuarios.mensajes.exito_eliminacion',array(),'arquitectura'
                )
            ));
        } catch (\Exception $e){
            return new JsonResponse(array(
                'type' => 'success',
                'message' => $this->get('translator')->trans(
                    'gestion_usuarios.mensajes.error_eliminacion',array(),'arquitectura'
                )
            ));
        }
    }
}
