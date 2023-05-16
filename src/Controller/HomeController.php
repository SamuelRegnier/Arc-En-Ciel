<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/legalNotice', name: 'legal_notice')]
    public function legalNotice(): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/legalNotice.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
