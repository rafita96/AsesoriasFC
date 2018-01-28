<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AdministradorController extends Controller
{

    /**
     * @Route("/admin", name="admin_home")
     */
    public function homeAction()
    {
    	$name = "Manuel";
    	return $this->render('admin/home.html.twig', array('name' => $name)); 
    }

    /**
     * @Route("/admin/asesores", name="admin_asesores")
     */
    public function asesoresAction()
    {
    	$periodo = "2018-1";
    	$a1 = array(
    		"matricula" => "343173",
    	    "nombre" => "Manuel",
    	    "apaterno" => "Carrillo",
    	    "amaterno" => "Leon",
    	);
    	$a2 = array(
    		"matricula" => "342460",
    	    "nombre" => "Rafael",
    	    "apaterno" => "Peralta",
    	    "amaterno" => "Blanco",
    	);

    	$asesores = [$a1,$a2];
    	return $this->render('admin/asesores.html.twig', array('asesores' => $asesores,'periodo' => $periodo));
    }
}
