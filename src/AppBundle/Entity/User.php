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
}
