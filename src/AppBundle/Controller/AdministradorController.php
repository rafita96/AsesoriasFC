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
    	return $this->render('admin/admin_home.html.twig', array('name' => $name));
    }
}
