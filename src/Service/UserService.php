<?php
/**
 * Created by PhpStorm.
 * User: ihor
 * Date: 18.05.18
 * Time: 23:36
 */

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * User constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $username
     * @return boolean
     */
    public function isUserExist($username)
    {
        $repo = $this->em->getRepository(User::class);
        $user = $repo->findOneBy(['username' => $username]);

        if ($user != null && $user->getUsername() == $username && $username != "") {
            return true;
        } else {
            return false;
        }
    }
}
