<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AdministradorController extends Controller
{
	/**
     * @Route("/admin/login", name="admin_login")
     */
    public function loginAction()
    {
    	return new Response("Hola Admin");
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function homeAction()
    {
    	$name = "Manuel";
    	return $this->render('admin/home.html.twig', array('name' => $name));
    }

    /**
     * @Route("/admin/asesores", name="asesores")
     */
    public function asesoresAction()
    {
    	$periodo = "2018-1";
    	$a1 = array(
    	    "nombre" => "Manuel",
    	    "apaterno" => "Carrillo",
    	    "amaterno" => "Leon",
    	);

    	$a1 = ["Manuel","Carrillo","Leon"];
    	$asesores = [$a1];
    	return $this->render('admin/asesores.html.twig');
    }
}
