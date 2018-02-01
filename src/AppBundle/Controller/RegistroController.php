<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Alumno;
use AppBundle\Form\AlumnoType;

class RegistroController extends Controller
{
    /**
     * @Route("/perfil/editar", name="registro")
     */
    public function defaultAction(Request $request)
    {
        $alumno = $this->getUser();

        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($alumno);
            $em->flush();

            return $this->redirectToRoute('perfil_alumno');
        }

        return $this->render('registro/registro.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/perfil/", name="perfil_alumno")
     */
    public function perfilAction(Request $request){
        $alumno = $this->getUser();
        $roles = $alumno->getRoles();
        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles)){

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('registro/perfil.html.twig',array(
            'alumno' => $alumno
        ));
    }
}
