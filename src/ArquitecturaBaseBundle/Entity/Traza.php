<?php

namespace ArquitecturaBaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Traza
 * @ORM\Table(name="Traza", schema="administracion")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\TrazaRepository")
 */
class Traza
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
     * @var \DateTime
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     * @ORM\Column(name="usuario", type="text", nullable=false)
     */
    private $usuario;

    /**
     * @var string
     * @ORM\Column(name="ip", type="text", nullable=false)
     */
    private $ip;

    /**
     * @var string
     * @ORM\Column(name="accion", type="text", nullable=false)
     */
    private $accion;

    /**
     * @var string
     * @ORM\Column(name="mac", type="text", nullable=true)
     */
    private $mac;

    /**
     * @var string
     * @ORM\Column(name="ruta", type="text", nullable=true)
     */
    private $ruta;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Traza
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Traza
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Traza
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set accion
     *
     * @param string $accion
     *
     * @return Traza
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set mac
     *
     * @param string $mac
     *
     * @return Traza
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get mac
     *
     * @return string
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     *
     * @return Traza
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
}
