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
use AppBundle\Form\LoginType;


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
        return $this->render('security/login.html.twig');
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