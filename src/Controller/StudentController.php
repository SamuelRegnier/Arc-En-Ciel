<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ClassroomRepository;
use App\Repository\LevelRepository;
Use Knp\Component\Pager\PaginatorInterface;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Student;
Use App\Form\StudentType;

class StudentController extends AbstractController
{
    #[Route('/student/select', name: 'student_select')]
    public function select(StudentRepository $studentRepository,
     ClassroomRepository $classroomRepository,
     LevelRepository $levelRepository,
     PaginatorInterface $paginator,
      Request $request,
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $classrooms = $classroomRepository -> findAll();
        $levels = $levelRepository -> findAll();

        if(($request->request->get('classe')) && (!$request->request->get('niveau'))){
            $classe = $request->request->get('classe');
            $students = $paginator->paginate(
                $student = $studentRepository -> findByClassroom($classe),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('student/read.html.twig', [
                'classrooms' => $classrooms,
                'students' => $students,
                'levels' => $levels,
        ]);
        } 
        if (($request->request->get('niveau')) && (!$request->request->get('classe'))){
            $level = $request->request->get('niveau');
            $students = $paginator->paginate(
                $student = $studentRepository -> findByLevel($level),
                $request->query->getInt('page', 1), 
                6
            );
            //dd($level);
            return $this->render('student/read.html.twig', [
                'levels' => $levels,
                'students' => $students,
                'classrooms' => $classrooms,
        ]);
        }
        if (($request->request->get('classe')) && ($request->request->get('niveau'))){
            $classe = $request->request->get('classe');
            $level = $request->request->get('niveau');
            //dd($level, $classe);
            $students = $paginator->paginate(
                $student = $studentRepository -> findByClassLevel($classe, $level),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('student/read.html.twig', [
                'levels' => $levels,
                'students' => $students,
                'classrooms' => $classrooms,
        ]);
        }
        $students = $paginator->paginate(
            $student = $studentRepository -> findAll(),
            $request->query->getInt('page', 1), 
            6
        );
        return $this->render('student/read.html.twig', [
            'classrooms' => $classrooms,
            'students' => $students,
            'levels' => $levels,
        ]);
    
    }

    #[Route('/student/select/{id}', name: 'student_select_id')]
    public function selectById(StudentRepository $repository,
     int $id
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        // On crée un nouveau tableau pour les parents
        $users = array();

        $student =  $repository ->findOneBy(['id' => $id]);

        // On récupère les parents présents dans la liste de parents de l'élève et on les ajoute dans le nouveau tableau
        foreach($student->getUsers() as $user){
            $users[] = $user;
        }
        
        return $this->render('student/select.html.twig', ['student' => $student, 'users' => $users]);
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

            // On récupère la selection de parents du questionnaire
            $users = $form->get('users')->getdata();

            // On ajoute chaque parent dans la liste de parents de l'élève via la méthode addUser
            foreach ($users as $user) {
                $student->addUser($user);
            }

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

        // On supprime tous les parents présents dans la liste de l'enfant
        foreach($student->getUsers() as $user){
            $student->removeUser($user);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère la selection de parents du questionnaire
            $users = $form->get('users')->getdata();

            // On ajoute chaque parent dans la liste de parents de l'élève via la méthode addUser
            foreach ($users as $user) {
                $student->addUser($user);
            }

            $manager->persist($student);
            $manager->flush();
            return $this->redirectToRoute('student_select');
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

