<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Alumno as Alumno;
use Symfony\Component\HttpFoundation\Response;

class AdministradorController extends Controller
{

    /**
     * @Route("/admin", name="admin_home")
     */
    public function homeAction(){

    	$user = $this->getUser();
    	$alumnos = $user->getAlumnos();
    	$asesores = array();
    	foreach ($alumnos as &$alumno) {    		
    	    if($alumno->getAsesor().""){
    	    	array_push($asesores,$alumno);
    	    }
    	}

    	return $this->render('admin/home.html.twig', array('asesores' => $asesores));
    }

    /**
	 * @Route("/admin/add", name="admin_add")
     */
    public function addAction(){
    	return new Response("Hola mundo!");
    }

    /**
	 * @Route("/admin/remove/{id}", defaults={"id" = -1}, name="admin_remove")
     */
    public function removeAction($id){

    	$user = $this->getUser();
    	$alumno = $this->getDoctrine()
    	        ->getRepository(Alumno::class)
    	        ->find($id);

    	$user->removeAlumno($alumno);
    	$alumno->removeUser($user);

    	$em = $this->getDoctrine()->getEntityManager();
    	$em->persist($user);
    	$em->persist($alumno);
    	$em->flush();

    	return $this->redirectToRoute('admin_home');
    }

}	
