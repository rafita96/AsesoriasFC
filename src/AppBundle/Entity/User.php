<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

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
     * @ORM\ManyToMany(targetEntity="Alumno", mappedBy="users")
     */
    private $alumnos;


    /**
     * Add alumno.
     *
     * @param \AppBundle\Entity\Alumno $alumno
     *
     * @return User
     */
    public function addAlumno(\AppBundle\Entity\Alumno $alumno)
    {
        $this->alumnos[] = $alumno;

        return $this;
    }

    /**
     * Remove alumno.
     *
     * @param \AppBundle\Entity\Alumno $alumno
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAlumno(\AppBundle\Entity\Alumno $alumno)
    {
        return $this->alumnos->removeElement($alumno);
    }

    /**
     * Get alumnos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlumnos()
    {
        return $this->alumnos;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return User
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
     * @return User
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
     * @return User
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

    public function isComplete(){
        if($this->nombre == '' || $this->aPaterno == '' || $this->aMaterno == '' || $this->email == ''){
            return false;
        }

        return true;
    }
}
