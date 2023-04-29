<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
Use Knp\Component\Pager\PaginatorInterface;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\User;
Use App\Form\UserType;

class UserController extends AbstractController
{
    #[Route('/user/select', name: 'user_select')]
    public function select(UserRepository $repository,
     PaginatorInterface $paginator,
      Request $request,
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $users = $paginator->paginate(
            $repository -> findAll(),
            $request->query->getInt('page', 1), 
            6
        );
        
        return $this->render('user/read.html.twig', ['users' => $users]);
    }

    #[Route('/user/select/{id}', name: 'user_select_id')]
    public function selectById(UserRepository $repository,
     int $id
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $students = array();
        $user =  $repository ->findOneBy(['id' => $id]);
        foreach($user->getStudent() as $student){
            $students[] = $student;
        }
        
        return $this->render('user/select.html.twig', ['user' => $user, 'students' => $students]);
    }

    #[Route('/user/new', name: 'user_create')]
    public function create(Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData() == $form->get('confirmationPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('user_select');
        }
        $this->addFlash('danger', 'Erreur lors de la confirmation du mot de passe!');
        return $this->redirectToRoute('user_create');
    }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/update/{id}', name: 'user_update')]
    public function edit(
    User $user,
    Request $request,
    EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function delete(
    User $user,
    EntityManagerInterface $manager
    ): Response
    {  

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('user_select');
        }

       $manager ->remove($user);
       $manager ->flush();

       return $this->redirectToRoute('user_select');
    }
}
