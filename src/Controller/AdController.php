<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdController extends Controller
{
    /**
     * @Route("/edit", name="create_ad")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createAd(Request $request, EntityManagerInterface $em)
    {
        $ad = new Ad();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setUser($user);
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('home', ['id' => $ad->getId()]);
        }

        return $this->render('', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_ad")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAd(Ad $ad, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('home', ['id' => $ad->getId()]);
        }

        return $this->render('', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_ad")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Ad $ad, Request $request, EntityManagerInterface $em)
    {
        $em->remove($ad);
        $em->flush();
        $page = $request->query->getInt('page', 1);

        return $this->redirectToRoute('home', ['page' => $page]);
    }
}
