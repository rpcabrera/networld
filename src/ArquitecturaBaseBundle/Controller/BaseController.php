<?php

namespace ArquitecturaBaseBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use ArquitecturaBaseBundle\Servicios\TrazasService;
use ArquitecturaBaseBundle\Gestores\AdministracionGtr;

class BaseController extends Controller
{
    public function inicioAction(){
        return $this->render('ArquitecturaBaseBundle:Default:index.html.twig');
    }

    //<editor-fold defaultstate="collapsed" desc="Métodos auxiliares">
    const Error = 'error';
    const Satisfactorio = 'satisfactorio';
    const Informacion = 'informacion';
    const Advertencia = 'advertencia';

    /**
     * Este método permite adicionar a los errores FlashBag
     * un tipo de alerta  y traducir el mensaje según el el filtro que está
     * activado en el sistema.
     * @param $key
     * @param $msgs
     */
    public function Alerta($key, $msgs) {
        foreach ($msgs as $msg) {
            $msg = $this->get('translator')->trans($msg);
            $this->get('session')->getFlashBag()->add($key, $msg);
        }
    }

    /**
     * Este Método retorna el Entity Manager
     * @return EntityManager
     */
    public function getEM()
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }

    /**
     * Este Método permite traducir el el mensaje según
     * el filtro que está activado en el sistema.
     * @param $msg
     * @return string
     */
    protected function traduceMensaje($msg) {
        $trans = $this->get('translator');
        /* @var $trans Translator */
        $msg = $trans->trans($msg);
        return $msg;
    }

    /**
     * @return TrazasService|object
     */
    public function getTraza()
    {
        return $this->get('administracion.traza');
    }

    /**
     * @return AdministracionGtr|object
     */
    public function getAdministracionGtr(){
        return $this->get('administracion.gestor');
    }

    //</editor-fold>



}
