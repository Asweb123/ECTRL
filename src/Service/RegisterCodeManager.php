<?php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class RegisterCodeManager
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em =$em;
    }

    public function NbOfUsersChecker($registerCode)
    {
        if (($registerCode === null) || (count($registerCode->getUsers()) >= $registerCode->getMaxNbOfUsers())) {
            return false;
        } else {
            return true;
        }
    }

    public function NbOfUsersUpdater($registerCode, $user)
    {
        $registerCode->addUser($user);
        $this->em->persist($registerCode);
        $this->em->flush();

        return true;
    }
}