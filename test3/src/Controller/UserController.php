<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;



#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Vérifier si l'utilisateur connecté est un administrateur
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si l'utilisateur n'est pas un administrateur, afficher une exception d'accès refusé
            throw new AccessDeniedHttpException('Vous n\'avez pas le droit d\'accéder à cette ressource.');
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    // Vérifier si l'utilisateur connecté est un administrateur
    if (!$this->isGranted('ROLE_ADMIN')) {
        // Si l'utilisateur n'est pas un administrateur, afficher une exception d'accès refusé
        throw new AccessDeniedHttpException('Vous n\'avez pas le droit d\'accéder à cette ressource.');
    }
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Encode the password before setting it on the user
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/new.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Security $security): Response
{
    $currentUser = $this->getUser();
    $isAdmin = $security->isGranted('ROLE_ADMIN');

    // Vérifier si l'utilisateur a le droit d'éditer cet utilisateur
    if (!$isAdmin && $currentUser !== $user) {
        throw new AccessDeniedHttpException('Vous n\'avez pas le droit d\'accéder à cette ressource.');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hasher le nouveau mot de passe s'il n'est pas vide
        $newPassword = $form->get('password')->getData();
        if (!empty($newPassword)) {
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
        }

        $entityManager->flush();

        // Redirection en fonction du rôle de l'utilisateur
        if ($isAdmin) {
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
        }
    }

    return $this->renderForm('user/edit.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

}
