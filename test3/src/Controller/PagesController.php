<?php

namespace App\Controller;

use App\Entity\Article; // N'oubliez pas d'importer l'entité Article
use Doctrine\ORM\EntityManagerInterface; // Importez l'EntityManagerInterface
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;

class PagesController extends AbstractController
{
    private $entityManager; // Déclarez une propriété privée pour stocker l'EntityManager

    // Injectez l'EntityManager dans le constructeur du contrôleur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $promotions = [
            ['id' => 1, 'image' => 'images/promotions/borne-arcade.jpg'],
            ['id' => 2, 'image' => 'images/promotions/gamecube.jpg'],
            ['id' => 3, 'image' => 'images/promotions/premiere ds.jpg'],
            ['id' => 4, 'image' => 'images/promotions/xbox1.jpg'],
            ['id' => 5, 'image' => 'images/promotions/mug-play.jpg'],
            ['id' => 6, 'image' => 'images/promotions/pull mario.jpg'],
            ['id' => 7, 'image' => 'images/promotions/tapis-souri.jpg'],
            ['id' => 8, 'image' => 'images/promotions/casquette.jpg'],
        ];

        return $this->render('home/index.html.twig', [
            'promotions' => $promotions,
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/description/{id}', name: 'description')]
    public function description(int $id): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            // Stockez un message dans la session flash pour afficher sur la page de connexion
            $this->addFlash('error', 'Vous devez être connecté pour accéder aux détails de l\'article.');

            // Redirigez l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_register');
        }

    // Utilisez l'EntityManager pour trouver l'article par son ID
    $article = $this->entityManager->getRepository(Article::class)->find($id);

    // Vérifiez si l'article existe
    if (!$article) {
        throw $this->createNotFoundException('L\'article n\'existe pas');
    }

    return $this->render('description/index.html.twig', [
        'article' => $article,
    ]);
}


#[Route('/jeux', name: 'app_jeux')]
public function jeux(): Response
{
    $jeux = [
        ['id' => 9, 'image' => 'images/jeux/Animal_Crossing.png', 'description' => 'Description du jeu Animal Crossing'],
        ['id' => 10, 'image' => 'images/jeux/crash-bandicoot-3.jpg', 'description' => 'Description du jeu Crash Bandicoot 3'],
        ['id' => 11, 'image' => 'images/jeux/resident-evil.jpg', 'description' => 'Description du jeu Resident Evil'],
        ['id' => 12, 'image' => 'images/jeux/sonic-hedgehog.jpg', 'description' => 'Description du jeu Sonic Hedgehog'],
        ['id' => 13, 'image' => 'images/jeux/street-fighter-II.jpg', 'description' => 'Description du jeu Street Fighter II'],
        ['id' => 14, 'image' => 'images/jeux/super-mario-64.jpg', 'description' => 'Description du jeu Super Mario 64'],
        ['id' => 15, 'image' => 'images/jeux/super-mario-bros-duck-hunt.jpg', 'description' => 'Description du jeu Super Mario Bros Duck Hunt'],
        ['id' => 16, 'image' => 'images/jeux/tomb-raider.jpg', 'description' => 'Description du jeu Tom Raider'],
    ];
    
    return $this->render('jeux/index.html.twig', [
        'jeux' => $jeux,
    ]);
}


    #[Route('/consoles', name: 'app_consoles')]
    public function consoles(): Response
    {
        $consoles = [
            ['id' => 17, 'image' => 'images/consoles/nes.png', 'description' => 'Description de la console NES'],
            ['id' => 18, 'image' => 'images/consoles/super-nintendo.jpg', 'description' => 'Description de la console Super Nintendo'],
            ['id' => 19, 'image' => 'images/consoles/game boy.jpg', 'description' => 'Description de la console Game Boy'],
            ['id' => 20, 'image' => 'images/consoles/mega drive.png', 'description' => 'Description de la console Mega Drive'],
            ['id' => 21, 'image' => 'images/consoles/nintendo 64.jpg', 'description' => 'Description de la console Nintendo 64'],
            ['id' => 22, 'image' => 'images/consoles/ps1.png', 'description' => 'Description de la console PlayStation 1'],
            ['id' => 23, 'image' => 'images/consoles/atari 2600.jpg', 'description' => 'Description de la console Atari 2600'],
            ['id' => 24, 'image' => 'images/consoles/dreamcast.jpg', 'description' => 'Description de la console Dreamcast'],
        ];

    return $this->render('consoles/index.html.twig', [
        'consoles' => $consoles,
    ]);
}



    #[Route('/goodies', name: 'app_goodies')]
    public function goodies(): Response
    {
        $goodies = [
            ['id' => 25, 'image' => 'images/goodies/mug.jpg', 'description' => 'Description du mug'],
            ['id' => 26, 'image' => 'images/goodies/sweat.jpg', 'description' => 'Description du sweat à capuche'],
            ['id' => 27, 'image' => 'images/goodies/stylo.jpg', 'description' => 'Description du stylo'],
            ['id' => 28, 'image' => 'images/goodies/pikachu.jpg', 'description' => 'Description de la peluche pikachu'],
            ['id' => 29, 'image' => 'images/goodies/lampe.jpg', 'description' => 'Description de la lampe'],
            ['id' => 30, 'image' => 'images/goodies/porte-cle.jpg', 'description' => 'Description du porte-clé'],
            ['id' => 31, 'image' => 'images/goodies/sous-verre.jpg', 'description' => 'Description des sous-verres'],
            ['id' => 32, 'image' => 'images/goodies/t shirt.jpg', 'description' => 'Description du t-shirt'],
        ];

    return $this->render('goodies/index.html.twig', [
        'goodies' => $goodies,
    ]);
}


    #[Route('/compte', name: 'app_compte')]
    public function compte(Security $security): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // Récupérer les informations de l'utilisateur
        $user = $this->getUser();

        return $this->render('compte/index.html.twig', [
            'user' => $user,
        ]);
    }

     #[Route('/article/{id}/modifier', name: 'modifier_article')]
     
    public function modifierArticle(Request $request, Article $article): Response
    {
        // Vérifiez si l'utilisateur est autorisé à modifier l'article (par exemple, vérifiez son rôle)
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet article.');
        }

        // Créez un formulaire pour modifier l'article
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirigez l'utilisateur vers la page de description de l'article
            return $this->redirectToRoute('description_article', ['id' => $article->getId()]);
        }

        // Affichez le formulaire de modification de l'article
        return $this->render('article/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

