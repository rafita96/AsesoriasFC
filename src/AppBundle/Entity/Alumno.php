<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Alumno
 *
 * @ORM\Table(name="alumno")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlumnoRepository")
 */
class Alumno implements UserInterface, \Serializable
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
     * @ORM\Column(name="matricula", type="string", length=10, unique=true)
     */
    private $matricula;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="aPaterno", type="string", length=100, nullable=true)
     */
    private $aPaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="aMaterno", type="string", length=100, nullable=true)
     */
    private $aMaterno;

    /**
     * @ORM\OneToMany(targetEntity="Horario", mappedBy="alumno", cascade={"persist"})
     */
    private $horarios;

    /**
     * @var bool
     *
     * @ORM\Column(name="asesor", type="boolean", options={"default" : 0})
     */
    private $asesor;

    /**
     * @ORM\OneToMany(targetEntity="Cita", mappedBy="alumno, asesor", cascade={"persist"})
     */
    private $citas;

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
     * Set matricula
     *
     * @param string $matricula
     *
     * @return Alumno
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Alumno
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
     * Set aPaterno
     *
     * @param string $aPaterno
     *
     * @return Alumno
     */
    public function setAPaterno($aPaterno)
    {
        $this->aPaterno = $aPaterno;

        return $this;
    }

    /**
     * Get aPaterno
     *
     * @return string
     */
    public function getAPaterno()
    {
        return $this->aPaterno;
    }

    /**
     * Set aMaterno
     *
     * @param string $aMaterno
     *
     * @return Alumno
     */
    public function setAMaterno($aMaterno)
    {
        $this->aMaterno = $aMaterno;

        return $this;
    }

    /**
     * Get aMaterno
     *
     * @return string
     */
    public function getAMaterno()
    {
        return $this->aMaterno;
    }
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->horarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->matricula;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->matricula;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

     /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->matricula,
            $this->matricula,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->matricula,
            $this->matricula,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    public function __toString()
    {
        return '{matricula:'.$this->matricula.'}';
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Alumno
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add horario
     *
     * @param \AppBundle\Entity\Horario $horario
     *
     * @return Alumno
     */
    public function addHorario(\AppBundle\Entity\Horario $horario)
    {
        $this->horarios[] = $horario;

        return $this;
    }

    /**
     * Remove horario
     *
     * @param \AppBundle\Entity\Horario $horario
     */
    public function removeHorario(\AppBundle\Entity\Horario $horario)
    {
        $this->horarios->removeElement($horario);
    }

    /**
     * Get horarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Set asesor
     *
     * @param boolean $asesor
     *
     * @return Alumno
     */
    public function setAsesor($asesor)
    {
        $this->asesor = $asesor;

        return $this;
    }

    /**
     * Get asesor
     *
     * @return boolean
     */
    public function getAsesor()
    {
        return $this->asesor;
    }

    /**
     * Add cita
     *
     * @param \AppBundle\Entity\Cita $cita
     *
     * @return Alumno
     */
    public function addCita(\AppBundle\Entity\Cita $cita)
    {
        $this->citas[] = $cita;

        return $this;
    }

    /**
     * Remove cita
     *
     * @param \AppBundle\Entity\Cita $cita
     */
    public function removeCita(\AppBundle\Entity\Cita $cita)
    {
        $this->citas->removeElement($cita);
    }

    /**
     * Get citas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCitas()
    {
        return $this->citas;
    }
}
