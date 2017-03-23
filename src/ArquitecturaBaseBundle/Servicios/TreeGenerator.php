<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 12/09/16
 * Time: 16:07
 */

namespace ArquitecturaBaseBundle\Servicios;


use ArquitecturaBaseBundle\Entity\Rol;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TreeGenerator
{
    /**
     * @var EntityManager
     */
    private $container;

    /**
     * TreeGenerator constructor.
     * @param $container ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function encodeRolNode(Rol $rol){
        return array(
            'text' => $rol->getNombre(),
            'icon' => 'glyphicon glyphicon-user',
            'selectedIcon' => 'glyphicon glyphicon-user',
            'color' => '#ffffff',
            'backColor' => '#8ab1ff',
            'href' => '#'.$rol->getId(),
            'selectable' => true,
            'state' => array(
                'checked' => false,
                'disabled' => false,
                'expanded' => true,
                'selected' => false
            ),
            'tags' => 'avaible',
            'idNodo' => $rol->getId()
        );
    }

    /**
     * @param $rol Rol
     * @return array
     */
    private function recursiveRolTreeGeneratorFunction($rol){
        if ($rol->getRolesHijos()->isEmpty()){
            return $this->encodeRolNode($rol);
        }else{
            $roles = $rol->getRolesHijos();
            $rolesHijosEnc = array();
            foreach ($roles as $rolHijo) {
                $rolHijoEnc = $this->recursiveRolTreeGeneratorFunction($rolHijo);
                $rolesHijosEnc[] = $rolHijoEnc;
            }
            $rolEnc = $this->encodeRolNode($rol);
            $rolEnc['nodes'] = $rolesHijosEnc;
            $rolEnc['icon'] = 'glyphicon glyphicon-th';
            $rolEnc['selectedIcon'] = 'glyphicon glyphicon-th';
            return $rolEnc;
        }
    }

    public function generarArbolRoles(){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $rolPadre = $em->getRepository('ArquitecturaBaseBundle:Rol')->buscarRolPrincipal();
        $encodedRolPadre =  $this->recursiveRolTreeGeneratorFunction($rolPadre);
        return array('roles' => array($encodedRolPadre));
    }
}