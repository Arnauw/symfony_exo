<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function homepage(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }

    #[Route('/create', name: 'app_create_form')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /**
         *
         */
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice', 'Successfully created');
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
