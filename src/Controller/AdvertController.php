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
 * @Route("/advert")
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
        return $this->render('advert/index.html.twig', ['adverts' => $advertRepository->findAll()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="advert_new", methods="GET|POST")
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
     * @Route("/{id}", name="advert_show", methods="GET")
     */
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', ['advert' => $advert]);
    }

    /**
     * @Route("/{id}/edit", name="advert_edit", methods="GET|POST")
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
     * @Route("/{id}", name="advert_delete", methods="DELETE")
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
