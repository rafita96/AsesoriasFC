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
     * @Route("/solicitud/new", name="citas_new")
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
            $cita->setEstado($cita->PENDIENTE);

            // Matriz de dias:
            // 0 al 4 son los dias lunes a viernes de 8 a 10
            // 5 al 9 son los dias lunes a viernes de 10 a 12
            // 10 al 14 son los dias lunes a viernes de 12 a 2
            // 15 al 19 son los dias lunes a viernes de 2 a 4
            // 20 al 24 son los dias lunes a viernes de 4 a 6
            $horario = json_decode("[".$cita->getHorario()."]");
            // Se ordenan de menor a mayor
            sort($horario, SORT_NUMERIC);
            $cita = $cita->setHorario($horario);

            // Partiendo del dia de hoy...
            date_default_timezone_set('America/Tijuana');
            $expiracion = new \DateTime();
            // Lunes=0 ... Viernes=4
            $dia = intval($expiracion->format('w')) - 1;

            // Debemos encontrar cuantos dias faltan para llegar al ultimo dia seleccionado
            $max = 0;
            $hora = 0;
            foreach ($cita->getHorario() as $dia_hora) {
                // Si el dia es menor al dia actual en realidad significa que es mayor.
                if($dia_hora%5 < $dia){
                    $tiempo = 6 - $dia_hora%5;
                    if($tiempo > $max){
                        $max = $tiempo;
                        $hora = floor($dia_hora/5)*2 + 8;
                    }
                }else{
                    // Si el dia es mayor al maximo, entonces se actualiza
                    if($dia_hora%5 > $max){
                        $max = $dia_hora%5;
                        $hora = floor($dia_hora/5)*2 + 8;
                    }
                }
            }

            $expiracion->modify("+".$max." days");
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
     *
     *
     */
    public function verAction(Request $request){
        $today = new \DateTime();
        $roles = $this->getUser()->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles)){
            return $this->redirectToRoute('homepage');
        }

        if ($this->getUser()->getAsesor()) {
            $asesor = $this->getUser();
            
            $repository = $this->getDoctrine()->getRepository(Cita::class);
            $citasRealizadas = $repository->findByAsesorRealizadas($asesor, $today);
            $citasPendientes = $repository->findByAsesorPendientes($asesor, $today);

            return $this->render('cita/lista_asesor.html.twig', array(
                'citasRealizadas' => $citasRealizadas,
                'citasPendientes' => $citasPendientes
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
     * @Route("/solicitud/eliminar/{id}", name="citas_eliminar")
     */
    public function eliminarAction(Request $request, $id){
        $alumno = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Cita::class);
        $cita = $repository->findById($id);
        if(!$cita){
            throw new NotFoundHttpException("La solicitud no existe");
        }

        $cita = $repository->findByAlumnoCita($alumno, $id);

        if($cita->getAsesor() != null){
            $this->addFlash('danger','Está cita no se puede eliminar.');
            return $this->redirectToRoute('citas');
        }

        if($cita){
            $em = $this->getDoctrine()->getManager();
            $em->remove($cita);
            $em->flush();

            return $this->redirectToRoute('citas');
        }
        
        throw new NotFoundHttpException("No eres el dueño de esta solicitud.");
    }

    /**
     * @Route("/solicitudes/", name="solicitudes")
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
            date_default_timezone_set('America/Tijuana');
            $today = new \DateTime();
            $citas = $repository->findByDisponibles($today);

            return $this->render('cita/solicitudes.html.twig', array(
                'citas' => $citas,
            )); 
        }

        return $this->redirectToRoute('citas');
    }

    /**
     * @Route("/solicitud/detalles/{id}", name="solicitudes_detalles")
     */
    public function detallesAction(Request $request, $id){

        if(!$this->getUser()->isComplete()){
            $this->addFlash('danger','No puedes aceptar una cita si no has completado tu perfil.');
            return $this->redirectToRoute('registro');
        }

        $repository = $this->getDoctrine()->getRepository(Cita::class);
        $cita = $repository->findOneById($id);
        
        if($cita){
            return $this->render('cita/detalles.html.twig', array(
                'cita' => $cita,
            )); 
        }
        
        throw new NotFoundHttpException("Elemento no encontrado.");
    }

    /**
     * @Route("/solicitud/aceptar", name="solicitudes_aceptar")
     */
    public function aceptarAction(Request $request, \Swift_Mailer $mailer){
        $id = $request->request->get('id');

        $repository = $this->getDoctrine()->getRepository(Cita::class);
        $cita = $repository->findOneById($id);

        $roles = $this->getUser()->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles) ||
            !($this->getUser()->getAsesor())){
            return $this->render('cita/detalles.html.twig', array(
                'cita' => $cita,
            ));
        }

        if($cita->getAsesor()){
            throw new NotFoundHttpException("La cita fue agendada por otra persona.");
        }

        $fecha = $request->request->get('fecha');
        $hora = $request->request->get('hora');

        $date = new \DateTime($fecha." ".$hora.":0:0");
        $cita->setFecha($date);

        $expiracion = clone $date;
        $expiracion->modify("+2 hours");
        $cita->setExpiracion($expiracion);
        $cita->setAsesor($this->getUser());

        $cita->setEstado($cita->CITADO);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cita);
        $em->flush();

        $message = (new \Swift_Message('Tu solicitud ha sido aprobada.'))
        ->setFrom('asesoriasfc@uabc.ens.mx')
        ->setTo($cita->getAlumno()->getCorreo()."@uabc.edu.mx")
        ->setBody(
            $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'emails/aprobada.html.twig',
                array('asesor' => $this->getUser(),
                        'cita' => $cita)
            ),
            'text/html'
        )
        ;

        $mailer->send($message);

        return $this->redirectToRoute('citas'); 
    }
}
