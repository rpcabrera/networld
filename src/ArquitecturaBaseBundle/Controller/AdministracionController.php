<?php

namespace ArquitecturaBaseBundle\Controller;

use ArquitecturaBaseBundle\Entity\Adapters\UsuarioAjaxAdapter;
use ArquitecturaBaseBundle\Entity\Concesion;
use ArquitecturaBaseBundle\Entity\Menu;
use ArquitecturaBaseBundle\Entity\Rol;
use ArquitecturaBaseBundle\Entity\Usuario;
use ArquitecturaBaseBundle\Entity\Visual\BuscarTraza;
use ArquitecturaBaseBundle\Form\BuscarTrazaType;
use ArquitecturaBaseBundle\Form\ConcesionType;
use ArquitecturaBaseBundle\Form\MenuType;
use ArquitecturaBaseBundle\Form\RolType;
use ArquitecturaBaseBundle\Form\UsuarioType;
use ArquitecturaBaseBundle\Gestores\AdministracionGtr;
use ArquitecturaBaseBundle\Servicios\TrazasService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdministracionController extends BaseController
{
    //<editor-fold defaultstate="collapsed" desc="Comunes">
    /**
     * @var AdministracionGtr
     */
    protected $AdministracionGtr;


    /**
     * @return AdministracionGtr
     */
    public function getAdministracionGtr()
    {
        return $this->get('administracion.gestor');
    }
    /**
     * @return TrazasService
     */
    public function getTraza()
    {
        return $this->get('administracion.traza');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ArquitecturaBaseBundle:Administracion:base_admin.html.twig');
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de usuarios">

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
        return $this->render('ArquitecturaBaseBundle:Administracion:crear_usuario.html.twig', array(
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
        return $this->render('ArquitecturaBaseBundle:Administracion:crear_usuario.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarUsuariosAction(){
        $em = $this->getEM();
        /** @var Usuario $usuarioAutenticado */
        $usuarioAutenticado = $this->getUser();
        $usuarios = $em->getRepository('ArquitecturaBaseBundle:Usuario')->listarExcepto($usuarioAutenticado->getId());
        return $this->render('@ArquitecturaBase/Administracion/listar_usuarios.html.twig', array(
            'usuarios' => $usuarios
        ));
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

    /**
     */
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

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de roles">
    /**
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function crearRolAction(Request $peticion){
        $em = $this->getDoctrine();
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
        return $this->render('@ArquitecturaBase/Administracion/crear_rol.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarRolesAction(){
        return $this->render('@ArquitecturaBase/Administracion/listar_roles.html.twig',array(

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

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de concesiones">
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

    /**
     * 1er Paso del Wizard para la gestion de la Concesion
     * En este paso se muestra solamente la vista inicial, donde se
     * dan los detalles de la creacion de la concesion, y se selecciona
     * el usuario al cual darle la concesion.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionarConcesionesAction(){

        return $this->render('ArquitecturaBaseBundle:Concesiones:concesiones_usuario.html.twig', array(
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

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de menu">

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

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de traza">

    /**
     * Este método permite registrar la traza correspondiente a la acción de deslogueo del usuario. Una vez registrada
     * dicha traza se redirecciona hacia la ruta:logout para que el framework se encargue de llevar a cabo la operación.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction()
    {
        $traza = $this->getTraza()->obtenerTraza(null);
        $user = $this->getUser()->getUsername();
        $fechaActual = new \DateTime();
        $accion = "Acción de deslogueo fue realizada correctamente.";
        $this->getTraza()->adicionarTraza($traza, $fechaActual, $accion, $user);
        return $this->redirectToRoute('logout');
    }

    public function buscarTrazaAction()
    {
        $buscarTrazaEV = new BuscarTraza();
        $form = $this->createForm(BuscarTrazaType::class , $buscarTrazaEV);
            return $this->render(
                '@ArquitecturaBase/Administracion/listar_trazas.html.twig',array(
                'form' => $form->createView()
                )
            );

    }

    public function ejecutarBuscarTrazaAction(Request $request)
    {
        $columnas = array(
            'getId',
            'getIndexGrid',
            'getNameToStringGrid',
            'getDescriptionToStringGrid',
            'getAcciones'
        );
        return new JsonResponse($this->Mostrar(
            $request,
            $columnas,
            'UserBundle:Action',
            'findActionsListGrid',
            false,
            false,
            array()
        ));
    }
    //</editor-fold>

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mostrarPerfilAction(){
        return $this->render('@ArquitecturaBase/Administracion/perfil.html.twig',array(

        ));
    }



    public function pruebaAction(){
        ld("PRUEBAS");
        /** @var Usuario $user */
        $user = $this->getUser();
        ld($user);

        $roles = $user->getRolesObjetos();
        ld($roles);

        foreach ($roles as $role){
            /** @var Rol $role */
            ld('Rol: '.$role->getNombre());
            ld('Concesiones: '.$role->getConcesiones()->count());
            $concesiones = $role->getConcesiones();
            foreach ($concesiones as $concesion){
                /** @var Concesion $concesion */
                ld('------------------------Concesion: -------------------------');
                ld('---Ruta: '.$concesion->getMenu()->getRuta());
                ld('---Nombre Menu: '.$concesion->getMenu()->getEtiqueta());
                ld('---Activa: '.$concesion->getActiva());
            }
        }

        ldd();
        return $this->render('@ArquitecturaBase/Administracion/prueba.html.twig');
    }


}

