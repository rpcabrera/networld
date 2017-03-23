<?php
/**
 * Created by PhpStorm.
 * User: cronos
 * Date: 14/01/17
 * Time: 4:11
 */

namespace ArquitecturaBaseBundle\Entity\Visual;


use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BuscarTraza
{
    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var StringInput
     */
    private $usuario;

    /**
     * @var StringInput
     */
    private $ip;

    /**
     * @var TextareaType
     */
    private $accion;

    /**
     * @var StringInput
     */
    private $mac;

    /**
     * @var StringInput
     */
    private $ruta;

    /**
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param \DateTime $fecha
     */
    public function setFecha($fecha)
    {
        if (is_null($fecha))
            $this->fecha->setTime(00, 00, 00);
        $this->fecha = $fecha;
    }

    /**
     * @return StringInput
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param StringInput $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return StringInput
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param StringInput $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return TextareaType
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * @param TextareaType $accion
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    /**
     * @return StringInput
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param StringInput $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return StringInput
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * @param StringInput $ruta
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }


}