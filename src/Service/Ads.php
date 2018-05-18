<?php
/**
 * Created by PhpStorm.
 * User: ihor
 * Date: 18.05.18
 * Time: 23:18
 */

namespace App\Service;


use App\Entity\Ad;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class Ads
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * LastPosts constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return Ad[]|array
     */
    public function getAds()
    {
        $repo = $this->em->getRepository(Ad::class);

        return $repo->findBy([], ['createdAt' => 'DESC']);
    }
}
