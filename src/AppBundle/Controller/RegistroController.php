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
     * @Route("registro")
     */
    public function defaultAction(Request $request)
    {
        $alumno = new Alumno();

        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alumno = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($alumno);
            $em->flush();

            return new Response("Éxito");
        }

        return $this->render('registro/registro.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
