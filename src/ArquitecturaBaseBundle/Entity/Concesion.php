<?php

namespace ArquitecturaBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concesion
 *
 * @ORM\Table(name="concesion", schema="seguridad")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\ConcesionRepository")
 */
class Concesion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="activa", type="boolean")
     */
    private $activa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechainicio", type="datetime")
     */
    private $fechainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechafin", type="datetime")
     */
    private $fechafin;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="concesiones")
     * @ORM\JoinColumn(name="idmenu", referencedColumnName="id")
     * @var Menu
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="concesiones")
     * @ORM\JoinColumn(name="idrol", referencedColumnName="id")
     * @var Rol
     */
    private $rol;

    /**
     * Concesion constructor.
     */
    public function __construct()
    {
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set activa
     *
     * @param boolean $activa
     * @return Concesion
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return boolean 
     */
    public function getActiva()
    {
        return $this->activa;
    }

    /**
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Concesion
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime 
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return Concesion
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime 
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return Rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param Rol $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }



}
