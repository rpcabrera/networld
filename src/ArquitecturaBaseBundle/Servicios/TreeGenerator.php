<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 12/09/16
 * Time: 16:07
 */

namespace ArquitecturaBaseBundle\Servicios;


use ArquitecturaBaseBundle\Entity\Menu;
use ArquitecturaBaseBundle\Entity\Rol;
use ArquitecturaBaseBundle\Gestores\AdministracionGtr;
use ArquitecturaBaseBundle\Repository\MenuRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TreeGenerator
{
    /**
     * @var ContainerInterface
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

    /**
     * @param Rol $rol
     * @return array
     */
    private function encodeRolNode($rol){
        return array(
            'text' => $rol->getNombre(),
            'icon' => 'glyphicon glyphicon-user',
            'selectedIcon' => 'glyphicon glyphicon-user',
            'color' => '#000000',
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
     * @param $menu Menu
     * @param $rol Rol
     * @return array
     * @internal param bool $tieneConcesion
     */
    private function encodeMenuNode($menu, $rol){
        /** @var AdministracionGtr $gtr */
        $gtr = $this->container->get('administracion.gestor');
        $tieneConcesion = $gtr->comprobarExisteConcesion($menu, $rol);

        return array(
            'text' => $menu->getEtiqueta(),
            'icon' => 'glyphicon '.$menu->getIcono(),
            'selectedIcon' => 'glyphicon '.$menu->getIcono(),
            'selectable' => true,
            'state' => array(
                'checked' => $tieneConcesion,
                'disabled' => false,
                'expanded' => true,
                'selected' => false
            ),
            'tags' => 'avaible',
            'idNodo' => $menu->getId()
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

    /**
     * @param $menu Menu
     * @param $rol Rol
     * @return array
     */
    private function recursiveMenuTreeGenerator($menu, $rol){
        if ($menu->getElementos()->isEmpty()){
            return $this->encodeMenuNode($menu, $rol);
        }else{
            $menus = $menu->getElementos();
            $childEncoded = array();
            foreach ($menus as $menuHijo) {
                $childEnc = $this->recursiveMenuTreeGenerator($menuHijo, $rol);
                $childEncoded[] = $childEnc;
            }
            $menuEncoded = $this->encodeMenuNode($menu, $rol);
            $menuEncoded['nodes'] = $childEncoded;
            $menuEncoded['icon'] = 'glyphicon '.$menu->getIcono();
            $menuEncoded['selectedIcon'] = 'glyphicon '.$menu->getIcono();
            return $menuEncoded;
        }
    }

    public function generarArbolRoles(){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $rolPadre = $em->getRepository('ArquitecturaBaseBundle:Rol')->buscarRolPrincipal();
        $encodedRolPadre =  $this->recursiveRolTreeGeneratorFunction($rolPadre);
        return array('roles' => array($encodedRolPadre));
    }

    /**
     * @param $idRol integer
     * @return array
     */
    public function generarArbolMenu($idRol){
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var MenuRepository $menuRp */
        $menuRp = $em->getRepository('ArquitecturaBaseBundle:Menu');
        $rol = $em->getRepository('ArquitecturaBaseBundle:Rol')->find($idRol);
        /** @var Menu[] $menus */
        $menus = $menuRp->listarMenusPadres();
        $encodedMenus = array();
        foreach ($menus as $menu) {
            $encodedMenu = $this->recursiveMenuTreeGenerator($menu, $rol);
            $encodedMenus[] = $encodedMenu;
        }
        return $encodedMenus;
    }



}