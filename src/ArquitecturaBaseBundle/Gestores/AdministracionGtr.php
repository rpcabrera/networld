<?php
/**
 * Created by PhpStorm.
 * User: rigoberto
 * Date: 8/09/16
 * Time: 14:39
 */

namespace ArquitecturaBaseBundle\Gestores;


use ArquitecturaBaseBundle\Entity\Concesion;
use ArquitecturaBaseBundle\Entity\Menu;
use ArquitecturaBaseBundle\Entity\Rol;
use ArquitecturaBaseBundle\Entity\Usuario;

class AdministracionGtr extends BaseGtr
{


    /**
     * Comprueba si existe una concesion en el sistema para el menu y el rol dado
     * @param $menu Menu Menu
     * @param $rol Rol
     * @return bool
     */
    public function comprobarExisteConcesion($menu,$rol){
        $qb = $this->getEm()->createQueryBuilder();
        $qb->select('c')
            ->from('ArquitecturaBaseBundle:Concesion','c')
            ->andWhere($qb->expr()->eq('c.rol',':prmRol'))
            ->andWhere($qb->expr()->eq('c.menu',':prmMenu'))
            ->setParameter('prmRol',$rol)
            ->setParameter('prmMenu',$menu)
        ;
        $concesiones = $qb->getQuery()->getResult();
        return count($concesiones) > 0;
    }


    public function listarMenusSinConcesiones(){
        $menuQB = $this->getEm()
                        ->getRepository('ArquitecturaBaseBundle:Menu')
                        ->buscarMenusSinConcesionesQB();
        $menus = $menuQB->getQuery()->getResult();
        return $menus;
    }

    public function DescartarRutas($listaRutas){
        $aDevolver = array();

        foreach ($listaRutas as $ruta) {
            $menu = $this->getEm()->getRepository('ArquitecturaBaseBundle:Menu')
                ->findOneBy(array(
                    'ruta' => $ruta
                ));
            if (is_null($menu)){
                $aDevolver[] = $ruta;
            }
        }
        return $aDevolver;
    }

    public function qb_MenusContenedores(){
        $menuQB = $this->getEm()
            ->getRepository('ArquitecturaBaseBundle:Menu')
            ->MenusContenedoresQB();
        return $menuQB;
    }

    public function ListarUsuarios(){
        $usuarios = $em = $this->getEm()
            ->getRepository('ArquitecturaBaseBundle:Usuario')
            ->findAll();
        return $usuarios;
    }

    public function qbUsuario()
    {
        return $this->getEm()->getRepository('ArquitecturaBaseBundle:Usuario')->qbUsuario();
    }

    /**
     * @param $menu Menu
     */
    public function salvarMenu($menu){
        $this->getEm()->persist($menu);
        $this->getEm()->flush();
    }

    /**
     * @param $rol Rol
     * @param $menus Menu[]
     */
    public function establecerConcesiones($rol, $menus){
        //Limpiar las concesiones anteriores del rol
        $cr = $this->getEm()->getRepository('ArquitecturaBaseBundle:Concesion');
        $cr->eliminarConcesionesDeRol($rol);
        //Agregar las nuevas concesiones
        foreach ($menus as $menu) {
            $concesion = new Concesion();
            $concesion->setActiva(true);
            $fechaInicio = new \DateTime();
            $concesion->setFechainicio($fechaInicio);
            $fechaFin = $fechaInicio->modify("+1 month");
            $concesion->setFechafin($fechaFin);
            $concesion->setMenu($menu);
            $concesion->setRol($rol);
            $this->getEm()->persist($concesion);
        }
    }

}