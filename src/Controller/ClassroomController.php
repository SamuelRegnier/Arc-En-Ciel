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
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;

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
                $classroom = $classroomRepository -> findByTeacher($enseignant),
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
    public function selectById(ClassroomRepository $classroomRepository,
     int $id
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $classrooms =  $classroomRepository ->findOneByTeacher($id);
        $classroom =  $classroomRepository ->findOneBy(['id' => $id]);

        //dd($classrooms, $classroom);
        
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

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('classroom_select');
        }

        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($classroom);
            $manager->flush();
            $this->addFlash('success', 'Création de la classe réalisée avec succès!');
            return $this->redirectToRoute('classroom_select');
        }
    
        return $this->render('classroom/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/classroom/update/{id}', name: 'classroom_update')]
    public function edit(
    Classroom $classroom,
    ClassroomRepository $classroomRepository,
    Request $request,
    EntityManagerInterface $manager,
    int $id,
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('classroom_select');
        }

        $form = $this->createForm(ClassroomType::class, $classroom);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('imageFile')){
                $classroom->setImageFile($form->get('imageFile')->getdata());
            }
            //dd($classroom);
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
    ClassroomRepository $classroomRepository,
    int $id,
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

        $classroom =  $classroomRepository ->findOneBy(['id' => $id]);
        
        foreach ($classroom->getStudents() as $student){
            if ($student->getClassroom()->getId() == $id){
                $student->setClassroom(null);
            }
        }

       $manager ->remove($classroom);
       $manager ->flush();

       return $this->redirectToRoute('classroom_select');
    }
}