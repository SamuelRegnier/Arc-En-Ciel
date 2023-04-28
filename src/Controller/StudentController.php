<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ClassroomRepository;
Use Knp\Component\Pager\PaginatorInterface;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Student;
Use App\Form\StudentType;

class StudentController extends AbstractController
{
    #[Route('/student/select', name: 'student_select')]
    public function select(StudentRepository $studentRepository,
     ClassroomRepository $classroomRepository,
     PaginatorInterface $paginator,
      Request $request,
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $classrooms = $classroomRepository -> findAll();

        if($request->request->get('classe')){
            $classe = $request->request->get('classe');
            $students = $paginator->paginate(
                $student = $studentRepository -> findByClassroom($classe),
                $request->query->getInt('page', 1), 
                6
            );
            

            return $this->render('student/read.html.twig', [
                'classrooms' => $classrooms,
                'students' => $students,
        ]);
        } else {
            $students = $paginator->paginate(
                $student = $studentRepository -> findAll(),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('student/read.html.twig', [
                'classrooms' => $classrooms,
                'students' => $students,
        ]);
        }
    }

    #[Route('/student/select/{id}', name: 'student_select_id')]
    public function selectById(StudentRepository $repository,
     int $id
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $student =  $repository ->findOneBy(['id' => $id]);
        
        return $this->render('student/select.html.twig', ['student' => $student]);
    }

    #[Route('/student/new', name: 'student_create')]
    public function create(Request $request,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($student);
            $manager->flush();
            return $this->redirectToRoute('student_select');
        }
    
        return $this->render('student/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/student/update/{id}', name: 'student_update')]
    public function edit(
    Student $student,
    Request $request,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('student/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function delete(
    Student $student,
    EntityManagerInterface $manager
    ): Response
    {  

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('student_select');
        }

       $manager ->remove($student);
       $manager ->flush();

       return $this->redirectToRoute('student_select');
    }
}

