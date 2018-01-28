<?php

namespace AppBundle\Security\User;

use AppBundle\Entity\Alumno;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Doctrine\ORM\EntityManager;

class WebserviceUserProvider implements UserProviderInterface
{   
    protected $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        // make a call to your webservice here
        $repository = $this->em->getRepository(Alumno::class);
        $alumno = $repository->findOneByMatricula($username);

        if($alumno){
            return $alumno;
        }

        $alumno = new Alumno();
        $alumno->setMatricula($username);
        $alumno->setIsActive(true);

        $this->em->persist($alumno);
        $this->em->flush();

        return $alumno;

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Alumno) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return Alumno::class === $class;
    }
}