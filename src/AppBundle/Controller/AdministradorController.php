<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\User;
use AppBundle\Entity\Alumno;
use AppBundle\Entity\Cita;

class AdministradorController extends Controller
{

    /**
     * @Route("/super/admin/", name="administradores")
     */
    public function administradoresAction(){

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository(User::class);
        $administradores = $repository->findByRole("ROLE_ADMIN");

        return $this->render('admin/administradores.html.twig', array(
            'administradores' => $administradores
        ));
    }

    /**
     * @Route("/super/admin/{id}", name="look_admin")
     */
    public function lookAdminAction(Request $request, User $admin)
    {
        $alumnos = $admin->getAlumnos();
        $asesores = array();
        foreach ($alumnos as &$alumno) {            
            if($alumno->getAsesor().""){
                array_push($asesores,$alumno);
            }
        }

        return $this->render('admin/home.html.twig', array(
            'asesores' => $asesores,
            'admin' => $admin,
        ));
    }

    /**
     * @Route("/admin/", name="admin_home")
     */
    public function homeAction(){

    	$user = $this->getUser();
        $roles = $user->getRoles();
        if(in_array("ROLE_SUPER_ADMIN", $roles))
        {
            return $this->redirectToRoute('administradores');
        }

    	$alumnos = $user->getAlumnos();
    	$asesores = array();
    	foreach ($alumnos as &$alumno) {    		
    	    if($alumno->getAsesor().""){
    	    	array_push($asesores,$alumno);
    	    }
    	}

    	return $this->render('admin/home.html.twig', array('asesores' => $asesores, 'admin' => null));
    }

    /**
	 * @Route("/admin/add/", name="admin_add")
     */
    public function addAction(Request $request){
        $matricula = $request->request->get('matricula');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository(Alumno::class);
        $alumno = $repository->findOneByMatricula($matricula);
        // Si no existe el alumno, crea uno
        if(is_null($alumno)){
            $alumno = new Alumno();
            $alumno->setMatricula($matricula);
            $alumno->setIsActive(true);
            $alumno->setAsesor(true);
            $validator = $this->get('validator');
            $errors = $validator->validate($alumno);
            if(count($errors) > 0){
                $this->addFlash('danger','La matrícula ingresada no es válida.');
                return $this->redirectToRoute('admin_home');
            }

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
        $alumno->setAsesor(true);
        $user->addAlumno($alumno);
        $alumno->addUser($user);

        $repositoryC = $this->getDoctrine()->getRepository(Cita::class);
        $solicitudes = $repositoryC->findBySolicitudes($alumno);
        foreach ($solicitudes as $solicitud) {
            $solicitud->setEstado($solicitud->EXPIRADO);
            $em->persist($solicitud);
            $em->flush();
        }

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
        $repository = $this->getDoctrine()->getRepository(Alumno::class);
    	$alumno = $repository->find($id);

    	$user->removeAlumno($alumno);
    	$alumno->removeUser($user);

    	$em = $this->getDoctrine()->getEntityManager();
    	$em->persist($user);
    	$em->persist($alumno);
    	$em->flush();

        $admin = $repository->findByHasUser($alumno);
        if($admin == null){
            $alumno->setAsesor(false);
            $em->persist($alumno);
            $em->flush();
        }

        $this->addFlash('success','El asesor fue eliminado correctamente.');
    	return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/admin/asesor/{matricula}", name="admin_asesor")
     */
    public function asesorAction($matricula){
        $user = $this->getUser();

        // $em = $this->getDoctrine()->getEntityManager();
        $repository = $this->getDoctrine()->getEntityManager()->getRepository(Alumno::class);
        $alumno = $repository->findOneByMatricula($matricula);
        // Si no existe el alumno
        if(is_null($alumno)){
            $this->addFlash('danger','No se pudo encontrar al asesor solicitado.');
            return $this->redirectToRoute('admin_home');                        
        }else{
            $roles = $user->getRoles();
            if(in_array("ROLE_SUPER_ADMIN", $roles)){
                $asesor_nombre = $alumno->getNombre()." ".$alumno->getAPaterno()." ".$alumno->getAMaterno();
                $citas_gen = $this->getDoctrine()
                            ->getEntityManager()
                            ->getRepository(Cita::class)->findAll();
                $citas = array();
                $nombres = array();
                foreach ($citas_gen as &$cita){
                    if($cita->getAsesor() == '{matricula:'.$matricula.'}'){
                        array_push($citas, $cita);
                        $asesorado = $repository->findOneById($cita->getAlumno());
                        $nombre = $asesorado->getNombre()." ".$asesorado->getAPaterno()." ".$asesorado->getAMaterno();
                        array_push($nombres, $nombre);
                    }
                }
                return $this->render('admin/asesor.html.twig', array('citas' => $citas, 'asesor_nombre' => $asesor_nombre, 'nombres' => $nombres));
            }
            $asesores = $user->getAlumnos();
            foreach ($asesores as &$asesor) {            
                if($asesor == $alumno){
                    $asesor_nombre = $asesor->getNombre()." ".$asesor->getAPaterno()." ".$asesor->getAMaterno();
                    $citas_gen = $this->getDoctrine()
                                ->getEntityManager()
                                ->getRepository(Cita::class)->findAll();
                    $citas = array();
                    $nombres = array();
                    foreach ($citas_gen as &$cita){
                        if($cita->getAsesor() == '{matricula:'.$matricula.'}'){
                            array_push($citas, $cita);
                            $asesorado = $repository->findOneById($cita->getAlumno());
                            $nombre = $asesorado->getNombre()." ".$asesorado->getAPaterno()." ".$asesorado->getAMaterno();
                            array_push($nombres, $nombre);
                        }
                    }
                    return $this->render('admin/asesor.html.twig', array('citas' => $citas, 'asesor_nombre' => $asesor_nombre, 'nombres' => $nombres));
                }
            }

            $this->addFlash('danger','El asesor solicitado no esta asociado a su cuenta.');
            return $this->redirectToRoute('admin_home');
        }
    }

}	
