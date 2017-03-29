<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 18/10/16
 * Time: 10:38
 */

namespace ArquitecturaBaseBundle\Servicios;

use ArquitecturaBaseBundle\Utiles\Funciones;
use ArquitecturaBaseBundle\Entity\Traza;
use ArquitecturaBaseBundle\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TrazasService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * TrazasService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }


    //<editor-fold defaultstate="collapsed" desc="Gestion de trazas">

    /**
     * Se retorna la entidad Traza nueva o de la base de datos en dependencia de del idtraza.
     * @param null|Traza $idtraza
     * @return Traza
     */
    public function obtenerTraza($idtraza)
    {
        if(is_null($idtraza))
        {
            return new Traza();
        }else{
            $em = $this->container->get('doctrine.orm.entity_manager');
            return $em->getRepository('ArquitecturaBaseBundle:Traza')->find($idtraza);
        }
    }

    /**
     * Este método registra la traza dado los parametros especificados.
     * @param $traza Traza
     * @param \DateTime $fecha
     * @param string $accion
     * @param Usuario $usuario
     * @param null $ruta
     * @return Traza|null|void
     */
    public function adicionarTraza($traza, $fecha, $accion, $usuario, $ruta = null)
    {
        $traza->setAccion($accion);
        $traza->setFecha($fecha);
        $traza->setUsuario($usuario);
        $traza->setMac(Funciones::getmac(Funciones::IpAddress()));
        $traza->setIp(Funciones::IpAddress());
        $traza->setRuta($ruta);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($traza);
        $em->flush();
    }

    //</editor-fold>
}