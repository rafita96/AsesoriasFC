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
     * @ORM\OneToMany(targetEntity="Alumno", mappedBy="user")
     */
    private $asesores;

    /**
     * Add asesore.
     *
     * @param \AppBundle\Entity\Alumno $asesore
     *
     * @return User
     */
    public function addAsesore(\AppBundle\Entity\Alumno $asesore)
    {
        $this->asesores[] = $asesore;

        return $this;
    }

    /**
     * Remove asesore.
     *
     * @param \AppBundle\Entity\Alumno $asesore
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAsesore(\AppBundle\Entity\Alumno $asesore)
    {
        return $this->asesores->removeElement($asesore);
    }

    /**
     * Get asesores.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsesores()
    {
        return $this->asesores;
    }
}
