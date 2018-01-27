<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cita;
use AppBundle\Entity\Horario;
use AppBundle\Form\CitaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CitaController extends Controller
{
    /**
     * @Route("citas/new")
     */
    public function newAction(Request $request)
    {
        $cita = new Cita();

        // dummy
        $horario1 = new Horario();
        $horario1->setDia(1);
        $horario1->setHorario('8:00 - 10:00');
        $cita->addHorario($horario1);
        $horario2 = new Horario();
        $horario2->setDia(0);
        $horario2->setHorario('8:00 - 10:00');
        $cita->addHorario($horario2);

        $form = $this->createForm(CitaType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('cita/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
