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

        $alumno = $this->getUser();
        if(!$alumno->isComplete()){
            return $this->redirectToRoute('registro');
        }

        $cita = new Cita();
        $form = $this->createForm(CitaType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cita->setAlumno($alumno);
            $cita->setEstado(0);

            $horario = json_decode("[".$cita->getHorario()."]");
            sort($horario);
            $cita = $cita->setHorario($horario);

            $max = $horario[0];
            foreach ($cita->getHorario() as $indice) {
                if($indice%5 == $max%5 && $indice > $max){
                    $max = $indice;
                }
                else if($indice%5 > $max%5){
                    $max = $indice;    
                }
            }

            date_default_timezone_set('America/Tijuana');
            $expiracion = new \DateTime();
            $dia = intval($expiracion->format('w'));

            if($dia > $max%5){
                $suma = (7 - $dia) + $max%5 + 1;
            }else{
                $suma = $dia - $max%5 + 1;
            }
            $expiracion->modify("+".$suma." days");

            $hora = floor($max/5)*2 + 8;
            $expiracion->setTime($hora, 0, 0);
            
            $cita->setExpiracion($expiracion);

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
        if ($this->getUser()->getAsesor()) {
            $asesor = $this->getUser();
            
            $repository = $this->getDoctrine()->getRepository(Cita::class);
            $citas = $repository->findByAsesor($asesor);

            return $this->render('cita/lista.html.twig', array(
                'citas' => $citas,
            ));
        }
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
        $cita = $repository->findById($id);
        if(!$cita){
            throw new NotFoundHttpException("La solicitud no existe");
        }

        $cita = $repository->findByAlumnoCita($alumno, $id);
        if($cita){
            $em = $this->getDoctrine()->getManager();
            $em->remove($cita);
            $em->flush();

            return $this->redirectToRoute('citas');
        }
        
        throw new NotFoundHttpException("No eres el dueño de esta solicitud.");
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
