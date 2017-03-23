<?php

namespace ArquitecturaBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantilla
 *
 * @ORM\Table(name="plantilla", schema="public")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\PlantillaRepository")
 */
class Plantilla
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
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="string")
     */
    private $contenido;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activa", type="boolean")
     */
    private $activa;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string")
     */
    private $descripcion;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param string $contenido
     */
    public function setContenido(string $contenido)
    {
        $this->contenido = $contenido;
    }

    /**
     * @return boolean
     */
    public function isActiva()
    {
        return $this->activa;
    }

    /**
     * @param boolean $activa
     */
    public function setActiva(bool $activa)
    {
        $this->activa = $activa;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion)
    {
        $this->descripcion = $descripcion;
    }







}
