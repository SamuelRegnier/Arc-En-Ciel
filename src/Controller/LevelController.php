<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LevelRepository;
Use Knp\Component\Pager\PaginatorInterface;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Level;
Use App\Form\LevelType;

class LevelController extends AbstractController
{
    #[Route('/level', name: 'app_level')]
    public function index(LevelRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $levels = $paginator->paginate(
            $repository -> findAll(),
            $request->query->getInt('page', 1), 
            5
        );
        
        return $this->render('level/index.html.twig', ['levels' => $levels]);
    }

    #[Route('/level/new', name: 'new_level')]
    public function new(Request $request,
    EntityManagerInterface $manager
    ): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($level);
            $manager->flush();
            return $this->redirectToRoute('app_level');
        }
        return $this->render('level/new.html.twig', ['form' => $form->createView()]);
    }
}
