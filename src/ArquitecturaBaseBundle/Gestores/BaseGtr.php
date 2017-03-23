<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 16/01/17
 * Time: 3:35
 */

namespace ArquitecturaBaseBundle\Gestores;


use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseGtr
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * BaseGtr constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param $consulta QueryBuilder
     * @return array
     */
    public function construirQBaArreglo($consulta) {
        $resultado = array();
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach ($consulta->getQuery()->getArrayResult() as $value) {
            $resultado[$value['idpersona']] = $value['primernombre'].' '.$value['segundonombre']
                .' '.$value['primerapellido'].' '.$value['segundoapellido'];
        }
        return $resultado;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }



}