<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cita;
use AppBundle\Entity\Horario;
use AppBundle\Form\CitaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Route("/citas/eliminar/{id}", name="citas_eliminar")
     */
    public function eliminarAction(Request $request, $id){
        $alumno = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Cita::class);
        $cita = $repository->findByAlumnoCita($alumno, $id);
        if($cita){
            $em = $this->getDoctrine()->getManager();
            $em->remove($cita);
            $em->flush();

            return $this->redirectToRoute('citas');
        }
        
        throw new NotFoundHttpException("La cita no existe o no eres el dueño.");
    }

    /**
     * @Route("/solicitudes", name="solicitudes")
     */
    public function solicitudesAction(Request $request){
        $roles = $this->getUser()->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles) ||
            $this->getUser()->getAsesor()){

            $repository = $this->getDoctrine()->getRepository(Cita::class);
            $citas = $repository->findByAsesor(null);

            return $this->render('cita/solicitudes.html.twig', array(
                'citas' => $citas,
            )); 
        }

        return $this->redirectToRoute('citas');
    }
}
