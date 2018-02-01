<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Alumno;

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
    public function addAction(Request $request){
        $matricula = $request->request->get('matricula');
        $user = $this->getUser();
        $alumnos = $user->getAlumnos();

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository(Alumno::class);
        $alumno = $repository->findOneByMatricula($matricula);
        // Si no existe el alumno, crea uno
        if(is_null($alumno)){
            $alumno = new Alumno();
            $alumno->setMatricula($matricula);
            $alumno->setIsActive(true);
            $alumno->setAsesor(true);
        }else{
            $asesores = $user->getAlumnos();
            foreach ($asesores as &$asesor) {            
                if($asesor == $alumno){
                    $this->addFlash('danger','Ese asesor ya fue agregado anteriormente.');
                    return $this->redirectToRoute('admin_home');
                }
            }
        }
        $this->addFlash('success','El asesor fue agregado correctamente.');
        $user->addAlumno($alumno);
        $alumno->addUser($user);

        $em->persist($user);
        $em->persist($alumno);
        $em->flush();

    	return $this->redirectToRoute('admin_home');
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

        $this->addFlash('success','El asesor fue eliminado correctamente.');
    	return $this->redirectToRoute('admin_home');
    }

}	
