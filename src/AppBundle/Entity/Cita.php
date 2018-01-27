<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cita
 *
 * @ORM\Table(name="cita")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CitaRepository")
 */
class Cita
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
     * @ORM\Column(name="tema", type="string", length=50)
     */
    private $tema;

    /**
     * @var string
     *
     * @ORM\Column(name="materia", type="string", length=50)
     */
    private $materia;

    /**
     * @var string
     *
     * @ORM\Column(name="expiracion", type="string", length=20)
     */
    private $expiracion;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var bool
     * 
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity="Horario", mappedBy="cita", cascade={"persist"})
     */
    private $horarios;




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
     * Set tema
     *
     * @param string $tema
     *
     * @return Cita
     */
    public function setTema($tema)
    {
        $this->tema = $tema;

        return $this;
    }

    /**
     * Get tema
     *
     * @return string
     */
    public function getTema()
    {
        return $this->tema;
    }

    /**
     * Set materia
     *
     * @param string $materia
     *
     * @return Cita
     */
    public function setMateria($materia)
    {
        $this->materia = $materia;

        return $this;
    }

    /**
     * Get materia
     *
     * @return string
     */
    public function getMateria()
    {
        return $this->materia;
    }

    /**
     * Set expiracion
     *
     * @param string $expiracion
     *
     * @return Cita
     */
    public function setExpiracion($expiracion)
    {
        $this->expiracion = $expiracion;

        return $this;
    }

    /**
     * Get expiracion
     *
     * @return string
     */
    public function getExpiracion()
    {
        return $this->expiracion;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Cita
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add horario
     *
     * @param \AppBundle\Entity\Horario $horario
     *
     * @return Cita
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Cita
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
