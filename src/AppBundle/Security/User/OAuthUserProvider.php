<?php

namespace AppBundle\Security\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\Alumno;

/**
 * Class OAuthUserProvider
 * @package AppBundle\Security\User
 */
class OAuthUserProvider extends BaseClass
{       
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        // $socialID = $response->getUsername();
        // $repository = $this->userManager->getRepository(Alumno::class);
        // $user = $repository->findOneBy(array($this->getProperty($response)=>$socialID));
        // // $user = $this->userManager->findUserBy(array($this->getProperty($response)=>$socialID));
        // $email = $response->getEmail();
        // $pattern = "/(uabc.edu.mx)$/";

        // if (is_null($socialID)){
        //     $this->container->get('security.context')->setToken(null);
        //     return null;
        // }
        // if(null === $user){
        //     if(preg_match($pattern, $email)){
        //         $user = new Alumno();
        //         $user->setEmail($email);
        //         $user->setPlainPassword(md5(uniqid()));
        //         $user->setEnabled(true);

        //         $service = $response->getResourceOwner()->getName();
        //         switch ($service) {
        //             case 'google':
        //                 $user->setGoogleID($socialID);
        //                 break;
        //         }

        //         $this->userManager->persist($user);
        //         $this->userManager->flush();
        //     }
        // }else{
        //     $checker = new UserChecker();
        //     $checker->checkPreAuth($user);   
        // }
 
        // return $user;

        $socialID = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response)=>$socialID));

        $pattern = "/(uabc.edu.mx)$/";
        $email = $response->getEmail();

        if(preg_match($pattern, $email)){
            return null;
        }
        //check if the user already has the corresponding social account
        if (null === $user) {
            //check if the user has a normal account
            $user = $this->userManager->findUserByEmail($email);
 
            if (null === $user || !$user instanceof UserInterface) {
                //if the user does not have a normal account, set it up:
                $user = $this->userManager->createUser();
                $user->setEmail($email);
                $user->setPlainPassword(md5(uniqid()));
                $user->setEnabled(true);
            }
            //then set its corresponding social id
            $service = $response->getResourceOwner()->getName();
            switch ($service) {
                case 'google':
                    $user->setGoogleID($socialID);
                    break;
                case 'facebook':
                    $user->setFacebookID($socialID);
                    break;
            }
            $this->userManager->updateUser($user);
        } else {
            //and then login the user
            $checker = new UserChecker();
            $checker->checkPreAuth($user);
        }
 
        return $user;
    }
}