<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\SecurityController as BaseController;

use AppBundle\Entity\Alumno;
use AppBundle\Form\AlumnoType;


class SecurityController extends BaseController {

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function renderLogin(array $data) {

        $request = $this->requestStack->getCurrentRequest();
        $requestAttributes = $request->attributes;
        $template = sprintf('security/admin_login.html.twig');
        
        return $this->container->get('templating')->renderResponse($template, $data);
    }

    /**
     * @Route("/login", name="login")
     */
    public function newAction(Request $request)
    {   
        $session = $request->getSession();
        
        if($session->get('matricula') !== null){
            return $this->redirectToRoute('homepage');
        }
        $user = new Alumno();

        $form = $this->createForm(AlumnoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Alumno::class);
            $realUser = $repository->findByMatricula($user->getMatricula());

            $session->invalidate();
            $session->set('matricula', $user->getMatricula());

            if($realUser == null){
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);

                $em->flush();
                return new Response("Guardado");
            }

            return new Response("Existe, entonces hay que guardarlo en la sesion.");

        }

        return $this->render('security/registro.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/session_logout", name="session_logout")
     */
    public function sessionLogoutAction(Request $request)
    {   
        $session = $request->getSession();
        
        if($session->get('matricula') !== null){
            $session->set('matricula', null);
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('homepage');
    }
}