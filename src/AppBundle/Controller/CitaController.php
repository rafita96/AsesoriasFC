<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cita;
use AppBundle\Entity\Horario;
use AppBundle\Form\CitaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CitaController extends Controller
{
    /**
     * @Route("/citas/new", name="citas_new")
     */
    public function newAction(Request $request)
    {
        $roles = $this->getUser()->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles) ||
            $this->getUser()->getAsesor()){
            return $this->redirectToRoute('citas');
        }

        $cita = new Cita();

        // dummy
        // $horario1 = new Horario();
        // $horario1->setDia(1);
        // $horario1->setHorario('8:00 - 10:00');
        // $cita->addHorario($horario1);
        // $horario2 = new Horario();
        // $horario2->setDia(0);
        // $horario2->setHorario('8:00 - 10:00');
        // $cita->addHorario($horario2);

        $form = $this->createForm(CitaType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alumno = $this->getUser();
            $cita->setAlumno($alumno);
            $cita->setEstado(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cita);
            $em->flush();

            return $this->redirectToRoute('citas');
        }

        return $this->render('cita/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/citas/", name="citas")
     */
    public function verAction(Request $request){
        $alumno = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Cita::class);
        $citas = $repository->findByAlumno($alumno);

        return $this->render('cita/lista.html.twig', array(
            'citas' => $citas,
        ));
    }

    /**
     * @Route("/solicitudes", name="solicitudes")
     */
    public function solicitudesAction(Request $request){
        $roles = $this->getUser()->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles) ||
            $this->getUser()->getAsesor()) {
            
            $id_cita = $request->request->get('cita_id');
            if (!empty($id_cita)) {
                $em = $this->getDoctrine()->getManager();

                $cita = $this->getDoctrine()->getRepository(Cita::class)->find($id_cita);
                $asesor = $this->getUser();
                $cita->setAsesor($asesor);
                $cita->setEstado(true);

                $em->persist($cita);
                $em->flush();
            }
            $repository = $this->getDoctrine()->getRepository(Cita::class);
            $citas = $repository->findByAsesor(null);

            return $this->render('cita/solicitudes.html.twig', array(
                'citas' => $citas,
            )); 
        }

        return $this->redirectToRoute('citas');
    }
}
