<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;

class CommandeController extends AbstractController
{
    #[Route('/valider-commande/{id}', name: 'valider_commande', methods: ['POST'])]
    public function valider(Request $request, Commande $commande, EntityManagerInterface $manager): Response
    {
        // Mettez ici la logique pour valider la commande, par exemple changer le statut
        $commande->setStatut('Validée');
        $manager->persist($commande);
        $manager->flush();

        // Rediriger vers la page du panier
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/annuler-commande/{id}', name: 'annuler_commande', methods: ['POST'])]
    public function annuler(Request $request, Commande $commande, EntityManagerInterface $manager): Response
    {
        // Mettez ici la logique pour annuler la commande, par exemple supprimer la commande
        $manager->remove($commande);
        $manager->flush();

        // Rediriger vers la page du panier
        return $this->redirectToRoute('panier_index');
    }
}
