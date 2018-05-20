<?php
/**
 * Created by PhpStorm.
 * User: ihor
 * Date: 20.05.18
 * Time: 22:43
 */

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setUsername('admin'.$i);
            $password = $this->encoder->encodePassword($user, $user->getUsername());
            $user->setPassword($password)
                ->setRoles(['ROLE_USER'])
                ->setIsActive(1);
            $manager->persist($user);

            for ($j = 0; $j < 4; $j++) {
                $ad = new Ad();
                $ad->setUser($user)
                    ->setTitle('Lorem ipsum dolor sit'.$j)
                    ->setDescription('
                        Lorem ipsum dolor sit amet, qui sumo delenit accusamus ei, vix mollis diceret blandit an, quot
                        aliquid delicatissimi quo an. Ridens aliquid appellantur duo eu. His modo hinc ornatus ei, 
                        vulputate persecuti nam ea. Cu eum populo antiopam argumentum, fabulas nominavi copiosae ius ne. 
                        Habeo harum labitur ex sit, ullum blandit at eum. Ut ius fuisset deleniti praesent, per etiam 
                        putant tamquam ex, ei vim munere postulant.
                    ');
                $manager->persist($ad);
            }
        }
        $manager->flush();
    }
}