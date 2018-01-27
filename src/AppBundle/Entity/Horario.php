<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Horario
 *
 * @ORM\Table(name="horario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HorarioRepository")
 */
class Horario
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
     * @var int
     *
     * @ORM\Column(name="dia", type="smallint")
     */
    private $dia;

    /**
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=25)
     */
    private $horario;

    /**
     * @ORM\ManyToOne(targetEntity="Cita", inversedBy="horarios")
     * @ORM\JoinColumn(name="cita_id", referencedColumnName="id")
     */
    private $cita;


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
     * Set dia
     *
     * @param integer $dia
     *
     * @return Horario
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return int
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set horario
     *
     * @param string $horario
     *
     * @return Horario
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set cita
     *
     * @param \AppBundle\Entity\Cita $cita
     *
     * @return Horario
     */
    public function setCita(\AppBundle\Entity\Cita $cita = null)
    {
        $this->cita = $cita;

        return $this;
    }

    /**
     * Get cita
     *
     * @return \AppBundle\Entity\Cita
     */
    public function getCita()
    {
        return $this->cita;
    }
}
