<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cita
 *
 * @ORM\Table(name="cita")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CitaRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    public $PENDIENTE = 0;
    public $CITADO = 1;
    public $FINALIZADO = 2;
    public $EXPIRADO = 3;
    
    /**
     * @var int
     * 
     * 0 - Pendiente
     * 1 - Citado
     * 2 - Finalizado
     * 3 - Expirado 
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="Alumno", inversedBy="citas")
     * @ORM\JoinColumn(name="alumno", referencedColumnName="id")
     */
    private $alumno;

    /**
     * @ORM\ManyToOne(targetEntity="Alumno", inversedBy="citas")
     * @ORM\JoinColumn(name="asesor", referencedColumnName="id")
     */
    private $asesor;

    /**
     * @var array
     *
     * @ORM\Column(name="horario", type="json_array")
     */
    private $horario;

    /**
     * @ORM\Column(name="dia_creado", type="datetime")
     */
    private $diaCreado;  

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */  
    private $fecha;

    /**
     * @ORM\Column(name="expiracion", type="datetime")
     */  
    private $expiracion;

    /**
     * @ORM\PrePersist
     */
    public function saveOnPrePersist()
    {
        $this->diaCreado = new \DateTime();
    }


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

    /**
     * Set alumno
     *
     * @param \AppBundle\Entity\Alumno $alumno
     *
     * @return Cita
     */
    public function setAlumno(\AppBundle\Entity\Alumno $alumno = null)
    {
        $this->alumno = $alumno;

        return $this;
    }

    /**
     * Get alumno
     *
     * @return \AppBundle\Entity\Alumno
     */
    public function getAlumno()
    {
        return $this->alumno;
    }

    /**
     * Set asesor
     *
     * @param \AppBundle\Entity\Alumno $asesor
     *
     * @return Cita
     */
    public function setAsesor(\AppBundle\Entity\Alumno $asesor = null)
    {
        $this->asesor = $asesor;

        return $this;
    }

    /**
     * Get asesor
     *
     * @return \AppBundle\Entity\Alumno
     */
    public function getAsesor()
    {
        return $this->asesor;
    }

    /**
     * Set horario
     *
     * @param array $horario
     *
     * @return Cita
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return array
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set diaCreado
     *
     * @param \DateTime $diaCreado
     *
     * @return Cita
     */
    public function setDiaCreado($diaCreado)
    {
        $this->diaCreado = $diaCreado;

        return $this;
    }

    /**
     * Get diaCreado
     *
     * @return \DateTime
     */
    public function getDiaCreado()
    {
        return $this->diaCreado;
    }

    /**
     * Set expiracion
     *
     * @param \DateTime $expiracion
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
     * @return \DateTime
     */
    public function getExpiracion()
    {
        return $this->expiracion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Cita
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
}
