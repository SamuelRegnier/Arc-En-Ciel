<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use App\Repository\UserRepository;
Use Knp\Component\Pager\PaginatorInterface;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Classroom;
Use App\Form\ClassroomType;

class ClassroomController extends AbstractController
{
    #[Route('/classroom/select/', name: 'classroom_select')]
    public function select(ClassroomRepository $classroomRepository,
                            UserRepository $userRepository,
                            PaginatorInterface $paginator,
                            Request $request,
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $users = $userRepository -> findAll();

        if($request->request->get('enseignant')){
            $enseignant = $request->request->get('enseignant');
            $classrooms = $paginator->paginate(
                $classroom = $classroomRepository -> findByExampleField($enseignant),
                $request->query->getInt('page', 1), 
                6
            );
            

            return $this->render('classroom/read.html.twig', [
                'classrooms' => $classrooms,
                'users' => $users,
        ]);
        } else {
            $classrooms = $paginator->paginate(
                $classroom = $classroomRepository -> findAll(),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('classroom/read.html.twig', [
                'classrooms' => $classrooms,
                'users' => $users,
        ]);
        }
    }

    #[Route('/classroom/select/{id}', name: 'classroom_select_id')]
    public function selectById(ClassroomRepository $repository,
     int $id
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $classroom =  $repository ->findOneBy(['id' => $id]);
        
        return $this->render('classroom/select.html.twig', ['classroom' => $classroom]);
    }

    #[Route('/classroom/new', name: 'classroom_create')]
    public function create(Request $request,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($classroom);
            $manager->flush();
            return $this->redirectToRoute('classroom_select');
        }
    
        return $this->render('classroom/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/classroom/update/{id}', name: 'classroom_update')]
    public function edit(
    Classroom $classroom,
    Request $request,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ClassroomType::class, $classroom);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($classroom);
            $manager->flush();
            return $this->redirectToRoute('classroom_select');
        }

        return $this->render('classroom/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/classroom/delete/{id}', name: 'classroom_delete')]
    public function delete(
    Classroom $classroom,
    EntityManagerInterface $manager
    ): Response
    {  

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('classroom_select');
        }

       $manager ->remove($classroom);
       $manager ->flush();

       return $this->redirectToRoute('classroom_select');
    }
}