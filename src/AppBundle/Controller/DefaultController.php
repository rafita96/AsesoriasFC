<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $alumno = $this->getUser();
        $roles = $alumno->getRoles();
        if (!in_array("ROLE_ADMIN", $roles)) {
            if (!in_array("ROLE_SUPER_ADMIN", $roles)) {
                if($alumno->getNombre() == '' || $alumno->getAPaterno() == '' || $alumno->getAMaterno() == ''){
                    return $this->redirectToRoute('registro');
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
