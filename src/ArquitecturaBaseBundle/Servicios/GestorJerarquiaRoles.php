<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 14/07/16
 * Time: 12:47
 */

namespace ArquitecturaBaseBundle\Servicios;


use ArquitecturaBaseBundle\Entity\Rol;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class GestorJerarquiaRoles extends RoleHierarchy
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * GestorJerarquiaRoles constructor.
     * @param array $jerarquia
     * @param ContainerInterface $container
     */
    public function __construct(array $jerarquia, ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($this->construirArbolJerarquia());
    }

    public function construirArbolJerarquia(){
        $jerarquia = array();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $roles = $em->getRepository('ArquitecturaBaseBundle:Rol')->findAll();
        foreach ($roles as $rol) {
            $arrayRolesHijos = array();
            foreach ($rol->getRolesHijos() as $rolHijo) {
                /** @var Rol $rolHijo */
                $arrayRolesHijos[] = $rolHijo->getNombre();
            }
            $jerarquia[$rol->getNombre()] = $arrayRolesHijos;
        }
        return $jerarquia;
    }






}