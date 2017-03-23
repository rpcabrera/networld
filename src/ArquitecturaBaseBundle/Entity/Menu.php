<?php

namespace ArquitecturaBaseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu", schema="administracion")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\MenuRepository")
 */
class Menu
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
     * @var string
     *
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255 , nullable = true)
     */
    private $ruta;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(name="icono", type="string")
     * @var string
     */
    private $icono;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="padre")
     * @var ArrayCollection
     */
    private $elementos;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="elementos", cascade={"all"})
     * @ORM\JoinColumn(name="idpadre", referencedColumnName="id")
     * @var Menu
     */
    private $padre;

    /**
     * @ORM\OneToMany(
     *      targetEntity = "ArquitecturaBaseBundle\Entity\Concesion",
     *      mappedBy="menu",
     *      cascade = {"all"}
     * )
     * @var ArrayCollection
     */
    private $concesiones;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->concesiones = new ArrayCollection();
        $this->elementos = new ArrayCollection();
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
     * Set etiqueta
     *
     * @param string $etiqueta
     * @return Menu
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return string 
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return Menu
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Menu
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * @return string
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * @param string $icono
     */
    public function setIcono($icono)
    {
        $this->icono = $icono;
    }

    /**
     * @return ArrayCollection
     */
    public function getElementos()
    {
        return $this->elementos;
    }

    /**
     * @param ArrayCollection $elementos
     */
    public function setElementos($elementos)
    {
        $this->elementos = $elementos;
    }

    /**
     * @return Menu
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * @param Menu $padre
     */
    public function setPadre($padre)
    {
        $this->padre = $padre;
    }

    /**
     * @return ArrayCollection
     */
    public function getConcesiones()
    {
        return $this->concesiones;
    }

    /**
     * @param ArrayCollection $concesiones
     */
    public function setConcesiones($concesiones)
    {
        $this->concesiones = $concesiones;
    }



}
