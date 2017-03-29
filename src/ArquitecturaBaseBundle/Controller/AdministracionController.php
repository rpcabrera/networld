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
use ArquitecturaBaseBundle\Gestores\AdministracionGtr;
use ArquitecturaBaseBundle\Servicios\TrazasService;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ArquitecturaBaseBundle:Administracion:base_admin.html.twig');
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Gestion de traza">


    //</editor-fold>

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mostrarPerfilAction(){
        return $this->render('@ArquitecturaBase/Administracion/perfil.html.twig',array(

        ));
    }

    /**
     * Este método permite registrar la traza correspondiente a la acción de deslogueo del usuario. Una vez registrada
     * dicha traza se redirecciona hacia la ruta:logout para que el framework se encargue de llevar a cabo la operación.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function salirAction()
    {
        $traza = $this->getTraza()->obtenerTraza(null);
        $user = $this->getUser()->getUsername();
        $fechaActual = new \DateTime();
        $accion = "Acción de deslogueo fue realizada correctamente.";
        $this->getTraza()->adicionarTraza($traza, $fechaActual, $accion, $user);
        return $this->redirectToRoute('logout');
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

