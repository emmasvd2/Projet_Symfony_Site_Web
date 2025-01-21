<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Article1Type;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin/article')]
class AdminArticleController extends AbstractController
{
    #[Route('/', name: 'app_admin_article_index', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_article_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_article_show', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_article_edit', methods: ['GET', 'POST'])]
#[IsGranted("ROLE_ADMIN")]
public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(Article1Type::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Le formulaire a été soumis et les données sont valides

        // Enregistrez les modifications dans la base de données
        $entityManager->flush();

        // Rediriger l'utilisateur vers une autre page, par exemple la liste des articles
        return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
    }

    // Si le formulaire n'a pas été soumis ou s'il y a des erreurs de validation, afficher à nouveau le formulaire
    return $this->render('article/edit.html.twig', [
        'article' => $article,
        'form' => $form->createView(),
    ]);
}   

#[Route('/{id}', name: 'app_admin_article_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez que le token CSRF est valide
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            // Supprimez l'article
            $entityManager->remove($article);
            $entityManager->flush();

            // Redirigez l'utilisateur vers la liste des articles après la suppression
            return $this->redirectToRoute('app_admin_article_index');
        }

        // En cas de token CSRF invalide, redirigez l'utilisateur vers une autre page ou affichez un message d'erreur
        // Notez que cela ne devrait normalement pas se produire si le formulaire est soumis correctement
        return $this->redirectToRoute('app_admin_article_index'); // Redirection de secours
    }
}
