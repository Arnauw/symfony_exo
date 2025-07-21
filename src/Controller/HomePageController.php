<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;

use App\Repository\CrudRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/', name: 'home_page')]
    public function homepage(CrudRepository $crudRepo): Response
    {

        $datas = $crudRepo->findAll();


        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'datas' => $datas
        ]);
    }

    #[Route('/create', name: 'create_form')]
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
            return $this->redirectToRoute('home_page');
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, CrudRepository $crudRepo, EntityManagerInterface $em, $id): Response
    {

        $data = $crudRepo->find($id);
        $form = $this->createForm(CrudType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($data);
            $em->flush();

            $this->addFlash('notice', 'Successfully created');
            return $this->redirectToRoute('home_page');
        }

        return $this->render('home_page/index.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, CrudRepository $crudRepo, EntityManagerInterface $em, $id): Response
    {

        $data = $crudRepo->find($id);
        $form = $this->createForm(CrudType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($data);
            $em->flush();

            $this->addFlash('notice', 'Successfully created');
            return $this->redirectToRoute('home_page');
        }

        return $this->render('home_page/index.html.twig', [
            'data' => $data
        ]);
    }

}
