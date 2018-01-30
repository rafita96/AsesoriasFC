<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SuperAdminControllerController extends Controller
{
    /**
     * @Route("/superadmin/", name="superadmin_home")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {

        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        $admins = array();

        // Obtener el rol de cada usuario
        foreach ($users as $user) {
            $roles = $user->getRoles();
            // Cada usuario tiene al menos ROLE_USER.
            // Debemos iterar por cada rol que tenga.
            foreach ($roles as $role) {
                if (strcmp($role, "ROLE_ADMIN") == 0) { // http://php.net/manual/en/function.strcmp.php
                    array_push($admins, $user);
                    break;
                }
            }
        }

        return $this->render('superadmin/index.html.twig', array(
            'admins' => $admins
        ));
    }

}
