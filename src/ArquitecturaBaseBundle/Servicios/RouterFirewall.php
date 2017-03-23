<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 5/09/16
 * Time: 16:13
 */

namespace ArquitecturaBaseBundle\Servicios;


use ArquitecturaBaseBundle\Entity\Rol;
use ArquitecturaBaseBundle\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RouterFirewall
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * RouterFirewall constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Comprueba si el rol tiene o no permiso sobre la ruta suministrada.
     * @param $usuario Usuario El usuario
     * @param $ruta string La Ruta
     * @return bool Si tiene permiso o no el rol
     */
    public function isRouteGrantedForUser($usuario, $ruta){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $concesionRepository = $em->getRepository('ArquitecturaBaseBundle:Concesion');
        $tienePermiso = $concesionRepository->buscarConcesionesParaUsuarioyRuta($usuario,$ruta);
        return $tienePermiso;
    }

}