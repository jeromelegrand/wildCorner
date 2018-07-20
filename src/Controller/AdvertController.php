<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class AdvertController extends Controller
{
    /**
     * @Route("/", name="advert_index", methods="GET")
     * @param AdvertRepository $advertRepository
     * @return Response
     */
    public function index(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findBy([], [
            'updatedAt' => 'DESC'
        ]);

        return $this->render('advert/index.html.twig', ['adverts' => $adverts]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Security("has_role('ROLE_USER')")
     * @Route("/advert/new", name="advert_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $advert->setOwner($this->getUser());

            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('advert_index');
        }

        return $this->render('advert/new.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/advert/{id}", name="advert_show", methods="GET")
     * @param Advert $advert
     * @return Response
     */
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', ['advert' => $advert]);
    }

    /**
     * @Route("/advert/{id}/edit", name="advert_edit", methods="GET|POST")
     * @Security("has_role('ROLE_USER')")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Advert $advert
     * @return Response
     */
    public function edit(Request $request, Advert $advert): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advert_edit', ['id' => $advert->getId()]);
        }

        return $this->render('advert/edit.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/advert/{id}", name="advert_delete", methods="DELETE")
     * @param Request $request
     * @param Advert $advert
     * @return Response
     */
    public function delete(Request $request, Advert $advert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advert);
            $em->flush();
        }

        return $this->redirectToRoute('advert_index');
    }
}
