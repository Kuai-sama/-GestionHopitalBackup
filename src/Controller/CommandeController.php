<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\DateDeSortiePatientType;
use App\Repository\CommandeRepository;
use App\Repository\MedicamentRepository;
use App\Repository\PatientRepository;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(MedicamentRepository $medic): Response
    {
        $commandes = $medic->getCommandeARecep();

        dump($commandes);

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
                "commandes" => $commandes

        ]);
    }

    #[Route('/new_commande', name: 'app_commande_new')]
    public function dateSortieForm(Request $request,EntityManagerInterface $en): Response
    {
        $commande = new Commande();
        $commande->setFournisseur("Le fournisseur")->setEtat("En cours")->setQuantite(50);

        return $this->redirectToRoute("app_traitement");
    }

    #[Route('/reception/{idcommande}', name: 'reception')]
    public function pret(MedicamentRepository $medoc,CommandeRepository $co,$idcommande,EntityManagerInterface $en): Response
    {

        $commande = $co->findOneById($idcommande);
        $commande->setEtat("Reçu");
        $en->persist($commande);
        $en->flush();

        $medicament = $medoc->findCommande($commande->getId());
        $medicament[0]->setStock($medicament[0]->getStock() + $commande->getQuantite());
        $en->persist($medicament[0]);
        $en->flush();

        return $this->redirectToRoute("app_commande");
    }
}
