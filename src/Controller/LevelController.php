<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LevelRepository;
Use Knp\Component\Pager\PaginatorInterface;

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
}
