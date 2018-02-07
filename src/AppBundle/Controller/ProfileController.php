<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class ProfileController extends Controller
{
    /**
     * @Route("/admin/perfil/", name="admin_perfil")
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();
        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/admin/perfil/editar", name="admin_perfil_editar")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
