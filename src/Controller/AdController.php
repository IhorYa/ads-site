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

            return $this->redirectToRoute('show_ad', ['id' => $ad->getId()]);
        }

        return $this->render('ad/create_ad.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show_ad", requirements={"id": "\d+"})
     * @param Ad $ad
     * @param Request $request
     * @return Response
     */
    public function showAd(Ad $ad, Request $request)
    {
        $page = $request->query->getInt('page', 1);
        return $this->render('ad/show_ad.html.twig', [
            'ad' => $ad,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_ad", requirements={"id": "\d+"})
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAd(Ad $ad, Request $request, EntityManagerInterface $em)
    {
        if ($this->checkAuthor($ad)) {
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('show_ad', ['id' => $ad->getId()]);
        }

        return $this->render('ad/edit_ad.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_ad", requirements={"id": "\d+"})
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Ad $ad, Request $request, EntityManagerInterface $em)
    {
        if ($this->checkAuthor($ad)) {
            return $this->redirectToRoute('home');
        }
        $em->remove($ad);
        $em->flush();
        $page = $request->query->getInt('page', 1);

        return $this->redirectToRoute('home', ['page' => $page]);
    }

    /**
     * @param Ad $ad
     * @return bool
     */
    private function checkAuthor($ad)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUsername();
        $adAuthor = $ad->getUser()->getUsername();
        if ($currentUser != $adAuthor) {
            $this->addFlash('warning', "You can edit or delete only your's ads!");
            return true;
        }
    }
}
