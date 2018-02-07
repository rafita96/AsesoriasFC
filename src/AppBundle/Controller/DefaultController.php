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
                if(!$alumno->isComplete()){
                    return $this->redirectToRoute('registro');
                }
            }
        }

        if(in_array("ROLE_ADMIN", $roles) || in_array("ROLE_SUPER_ADMIN", $roles)){
            return $this->redirectToRoute('admin_home');
        }

        // replace this example code with whatever you need
        return $this->redirectToRoute('citas');
    }
}
