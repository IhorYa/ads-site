<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Ads;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends Controller
{
    /**
     * @var Ads $ads
     */
    private $ads;

    /**
     * @var UserService $isUserExist
     */
    private $isUserExist;

    public function __construct(Ads $ads, UserService $userService)
    {
        $this->ads = $ads;
        $this->isUserExist = $userService;
    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $username = $user->getUsername();
        $existUser = $this->isUserExist->isUserExist($username);

        if ($existUser) {
            return $this->authRredirect($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            return $this->authRredirect($request);
        }

        $listAds = $this->ads->getAds();
        $page = $request->query->getInt('page', 1);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listAds,
            $page,
            5
        );

        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function authRredirect($request)
    {
        return $this->redirectToRoute('login', [
            'request' => $request,
        ], 307);
    }
}
