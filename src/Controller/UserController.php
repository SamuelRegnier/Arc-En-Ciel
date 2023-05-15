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
Use App\Form\UpdateProfilType;
Use App\Form\UpdatePasswordType;

class UserController extends AbstractController
{
    #[Route('/user/select', name: 'user_select')]
    public function select(UserRepository $userRepository,
     PaginatorInterface $paginator,
      Request $request,
      ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if($request->request->get('search')){
            $name = '%'.$request->request->get('search').'%';
            $users = $paginator->paginate(
                $user = $userRepository -> findByName($name),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('user/read.html.twig', ['users' => $users]);
        }

        if($request->request->get('role')){
            $role = $request->request->get('role');
            $users = $paginator->paginate(
                $user = $userRepository -> findByRole($role),
                $request->query->getInt('page', 1), 
                6
            );
            return $this->render('user/read.html.twig', ['users' => $users]);
        }

        $users = $paginator->paginate(
            $userRepository -> findAllOrderBy(),
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

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('user_select');
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

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        if (!$isAdmin){
            return $this->redirectToRoute('user_select');
        }
        
        $form = $this->createForm(UpdateProfilType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('user_select_id', ['id'=> $user->getId()]);
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/mdp/{id}', name: 'user_updateMdp')]
    public function editMpd(
        User $user,
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() != $user){
            return $this->redirectToRoute('user_select_id', ['id'=> $user->getId()]);
        }
        
        $oldUser = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashMdpActuel = $passwordHasher->isPasswordValid(
                $oldUser,
                $form->get('password')->getData()
            );
            if($hashMdpActuel && $form->get('newPassword')->getData() == $form->get('confirmationPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('user_select_id', ['id'=> $user->getId()]);
            }

            $this->addFlash('danger', 'Erreur lors de la confirmation du mot de passe!');
            return $this->redirectToRoute('user_updateMdp');
        }

        return $this->render('user/updateMdp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function delete(
    User $user,
    UserRepository $userRepository,
    int $id,
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

        $user =  $userRepository ->findOneBy(['id' => $id]);
        if ($user->getClassroom() != null){
            $user->getClassroom()->setUser(null);
        }

        $manager ->remove($user);
        $manager ->flush();

       return $this->redirectToRoute('user_select');
    }
}
