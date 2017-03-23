<?php

namespace ArquitecturaBaseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rol
 *
 * @ORM\Table(name="rol", schema="seguridad")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\RolRepository")
 */
class Rol implements RoleInterface
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
     * @Assert\NotBlank(
     *      message = "El nombre no puede ser vacío"
     * )
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "La etiqueta no puede ser vacía"
     * )
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity = "Rol", inversedBy="rolesHijos", cascade="all")
     * @ORM\JoinColumn(name="idrolpadre", referencedColumnName="id")
     * @Assert\NotNull(
     *      message = "Debe seleccionar un rol padre para el este rol"
     * )
     * @var Rol
     */
    private $rolPadre;

    /**
     * @ORM\OneToMany(targetEntity = "Rol", mappedBy="rolPadre", cascade = {"all"})
     * @var ArrayCollection
     */
    private $rolesHijos;

    /**
     * @ORM\ManyToMany(targetEntity = "Usuario", mappedBy = "roles", cascade = {"all"})
     * @ORM\JoinTable(
     *      name="seguridad.usuario_rol",
     *      inverseJoinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(
     *      targetEntity = "ArquitecturaBaseBundle\Entity\Concesion",
     *      mappedBy="rol",
     *      cascade = {"all"}
     * )
     * @var ArrayCollection
     */
    private $concesiones;

    /**
     * Rol constructor.
     */
    public function __construct()
    {
        $this->rolesHijos = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
        $this->concesiones = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Rol
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set etiqueta
     *
     * @param string $etiqueta
     * @return Rol
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Rol
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Rol
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
     * @return Rol
     */
    public function getRolPadre()
    {
        return $this->rolPadre;
    }

    /**
     * @param Rol $rolPadre
     */
    public function setRolPadre($rolPadre)
    {
        $this->rolPadre = $rolPadre;
    }

    /**
     * @return ArrayCollection
     */
    public function getRolesHijos()
    {
        return $this->rolesHijos;
    }

    /**
     * @param ArrayCollection $rolesHijos
     */
    public function setRolesHijos($rolesHijos)
    {
        $this->rolesHijos = $rolesHijos;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * @param ArrayCollection $usuarios
     */
    public function setUsuarios($usuarios)
    {
        $this->usuarios = $usuarios;
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




    public function getRole()
    {
        return $this->getNombre();
    }


}
