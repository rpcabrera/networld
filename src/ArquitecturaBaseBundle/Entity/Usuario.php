<?php
namespace ArquitecturaBaseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", schema="seguridad")
 * @ORM\Entity(repositoryClass="ArquitecturaBaseBundle\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface,\Serializable
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "El nombre no debe ser vacío")
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(message = "El nombre no debe ser vacío")
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Su contraseña debe tener como mínimo {{ limit }} caracteres"
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var ArrayCollection
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "Debe seleccionar al menos un rol"
     * )
     * @ORM\ManyToMany(targetEntity = "Rol", inversedBy="usuarios")
     * @ORM\JoinTable(
     *      name="seguridad.usuario_rol",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @var string
     * @Assert\NotNull(message = "La descripción no debe ser vacía")
     * @Assert\NotBlank(message = "La descripción no debe ser vacía")
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     * @Assert\NotBlank(message = "El correo no debe ser vacío")
     * @Assert\Email(
     *       message = "El correo introducido no es válido."
     * )
     * @ORM\Column(name="correo", type="string", length=255)
     */
    private $correo;

    /**
     * @var string
     * @ORM\Column(name="foto", type="string", length=255)
     */
    private $foto;


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
     * @return Usuario
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
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }



    /**
     * Set roles
     *
     * @param array $roles
     * @return Usuario
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Usuario
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Usuario
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
     * Set correo
     *
     * @param string $correo
     * @return Usuario
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    /**
     * @return ArrayCollection
     */
    public function getRolesObjetos(){
        return $this->roles;
    }


    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        $arrayRoles = array();
        if ($this->roles != null){
            foreach ($this->roles as $rol) {
                /** @var Rol $rol */
                $arrayRoles[] = $rol->getNombre();
            }
        }
        return $arrayRoles;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->nombre,
            $this->password,
            $this->foto,
            $this->descripcion,
            $this->activo
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->nombre,
            $this->password,
            $this->foto,
            $this->descripcion,
            $this->activo
            ) = unserialize($serialized);
    }

    public function getUsername()
    {
        return $this->nombre;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getSalt()
    {
        return null;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \ArquitecturaBaseBundle\Entity\Rol $role
     *
     * @return Usuario
     */
    public function addRole(\ArquitecturaBaseBundle\Entity\Rol $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ArquitecturaBaseBundle\Entity\Rol $role
     */
    public function removeRole(\ArquitecturaBaseBundle\Entity\Rol $role)
    {
        $this->roles->removeElement($role);
    }


}
