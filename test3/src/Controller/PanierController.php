<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Commande;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Repository\userRepository; 
use App\Repository\CommandeRepository;

class PanierController extends AbstractController
{
    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter')]
    public function ajouterAuPanier(Request $request, Article $article, EntityManagerInterface $manager, Security $security, CommandeRepository $CommandeRepository): Response
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $security->getUser();

        // Vérifier si un utilisateur est authentifié
        if (!$user) {
            // Rediriger l'utilisateur vers la page de connexion ou afficher un message d'erreur
            return $this->redirectToRoute('app_home');
        }

            $commande = new Commande();
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut('En cours');
            $commande->setPrixTotal($article->getPrice());
            $commande->setUser($user); // Associer l'utilisateur à la commande


            // Enregistrer la commande en base de données
            $manager->persist($commande);
        

        // Enregistrer les changements en base de données
        $manager->flush();

        // Rediriger l'utilisateur vers la page du panier ou une autre page appropriée
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier', name: 'panier_index')]
    public function index(CommandeRepository $CommandeRepository): Response
{
    // Récupérer l'utilisateur actuellement authentifié
    $user = $this->getUser();
    
    // Initialisez la variable commandesEnCours à une collection vide
    $commandesEnCours = [];

    // Vérifier si l'utilisateur est authentifié
    if ($user) {
        // Récupérer les commandes en cours de l'utilisateur
        $commandesEnCours = $user->getCommandes();
        $commandes=[];
        foreach ($commandesEnCours as $commande ){
            if(strcmp($commande->getStatut(), "Validée")!=0){
                $commandes[]=$commande;
            }
        }
    } else {
        // Gérer le cas où l'utilisateur n'est pas authentifié, peut-être en redirigeant vers la page de connexion
        // ou en affichant un message d'erreur
        // ...

        // Vous pouvez également simplement retourner une réponse vide ou une page avec un message d'erreur
        $errorMessage = 'Vous devez être connecté pour accéder à votre panier.';
        return new Response($errorMessage, Response::HTTP_UNAUTHORIZED);
    }

    return $this->render('panier/index.html.twig', [
        'commandes' => $commandes,
    ]);
}
}