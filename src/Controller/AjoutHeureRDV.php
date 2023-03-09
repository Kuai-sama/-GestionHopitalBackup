<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Salle;

use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AjoutHeureRDV extends AbstractController
{

    #[Route('/listeRDV', name: 'listeRDV')]
    function view(EntityManagerInterface $em, SalleRepository $salleRepository): Response
    {
        $id = $this->getUser()->getId();
        // On récupère tout les rdvs liés à l'utilisateur
        $events = $em->getRepository(RDV::class)->findRDVByMedecinOuInfermier($id);

        $salles = $salleRepository->findAll();

        // On enlève tout les rendez-vous qui ont déjà une durée
        foreach ($events as $key => $event) {
            if ($event->getDuree() != null) {
                unset($events[$key]);
            }
        }
        return $this->render('Rdv/editRDV.html.twig', compact('events', 'salles'));
    }

    #[Route('/ajoutHeureRDV', name: 'ajoutHeureRDV')]
    function add(EntityManagerInterface $em, Request $request): Response
    {
        // On récupère les données du formulaire
        $data = $request->request->all();

        // On vérifie que la salle existe
        if ($em->getRepository(Salle::class)->getByEmplacementSalle($data['salle']) == null) {
            $this->addFlash('error', 'La salle n\'existe pas');
            //dd($data['salle']);
            return $this->redirectToRoute('listeRDV');
        }

        // On vérifie que la durée est bien un nombre et est comprise entre 15min et 24 heures
        if (!is_numeric($data['duree']) || $data['duree'] < 15 || $data['duree'] > 14405) {
            $this->addFlash('error', 'La durée doit être un nombre compris entre 15 min et 24h');
            return $this->redirectToRoute('listeRDV');
        }
        // On récupère le rendez-vous et la salle
        $rdv = $em->getRepository(RDV::class)->find($data['id']);
        $salle = $em->getRepository(Salle::class)->getByEmplacementSalle($data['salle']);

        // On hydrate l'objet RDV
        $rdv->setTitre($data['titre']);
        $rdv->setDuree($data['duree']);
        $rdv->setSalle($salle);
        $rdv->setValider(true);

        // On enregistre les modifications
        $em->persist($rdv);

        // On envoie les modifications à la base de données
        $em->flush();

        $this->addFlash('success', 'Le rendez-vous a été modifié');
        return $this->redirectToRoute('listeRDV');
    }

    #[Route('/addValidate', name: 'addValidate')]
    function addValidate(EntityManagerInterface $em, Request $request): Response
    {
        // On récupère les données du formulaire
        $data = $request->request->all();

        // On récupère le rendez-vous
        $rdv = $em->getRepository(RDV::class)->find($data['id']);

        if ($data['accomplished'] == 'true') {
            $rdv->setAccompli(true);
            $this->addFlash('success', 'Le rendez-vous a été effectué');
        } else {
            $rdv->setAccompli(false);
            $this->addFlash('success', 'Le rendez-vous n\'a pas été effectué');
        }
        $em->persist($rdv);
        $em->flush();
        return $this->redirectToRoute('listeRDV');
    }
}
