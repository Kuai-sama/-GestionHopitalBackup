<?php

namespace App\Controller;

use App\Entity\AppliquerPrescription;
use App\Entity\Diagnostic;
use App\Entity\Personne;
use App\Entity\Prescription;
use App\Form\AjoutDiagnosticType;
use App\Form\AjoutPrescriptionType;
use App\Form\PatientType;
use App\Repository\AppliquerPrescriptionRepository;
use App\Repository\PatientRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossierPatientController extends AbstractController
{
    #[Route('/dossier/{idpatient}', name: 'app_dossier_patient')]
    public function index($idpatient, PatientRepository $patient): Response
    {
        dump($patient->getPatInfo($idpatient));

        return $this->render('dossier_patient/index.html.twig', [
            'InfoPatient' => $patient->getPatInfo($idpatient),
        ]);
    }

    /*#[Route('/dossier/{idpatient}', name: 'app_dossier_patient')]
    public function dateSortieForm($idpatient, PatientRepository $patient,Request $request,EntityManagerInterface $en): Response
    {
        $personne = $patient->findOneById($idpatient);
        $form = $this->createForm(PatientType::class,$personne);
        $form->add('send',SubmitType::class,['label'=>'modifier/ajouter une date de sortie pour ce patient']);
        $form->handleRequest($request);



        if(($form->isSubmitted() && $form->isValid())){
            //form
            $en->persist($personne);
            $en->flush();
            //message
            $this->addFlash('info','ajout réussi');

            return $this->redirectToRoute('list_patient');
        }

        $reponse = new Response($this->render('dossier_patient/dateSortieForm.html.twig',['form'=>$form->createView()]));
        return $reponse;
    }*/

    #[Route('/ajoutDiagno/{idper}', name: 'app_dossier_patient_ajout_diagno')]
    public function AjoutDiagno(PersonneRepository $per,$idper,Request $request,EntityManagerInterface $en): Response
    {
        $diagnostic = new Diagnostic();
        $form = $this->createForm(AjoutDiagnosticType::class,$diagnostic);
        $form->add('send',SubmitType::class,['label'=>'ajouter un diagnostic a se patient']);
        $form->handleRequest($request);


        if(($form->isSubmitted() && $form->isValid())){
            //form


            $personne = $per->findOneById($idper);
            $personne->addDiagnostiquer($diagnostic);
            $en->persist($personne);
            $en->persist($diagnostic);
            $en->flush();
            //message
            $this->addFlash('info','ajout réussi');

            //diagnostic diagno
            //$per->AddPersoDiagno($idper,$diagnostic->getId());

            return $this->redirectToRoute('patient_list_patient');
        }

        $reponse = new Response($this->render('dossier_patient/DiagnoForm.html.twig',['form'=>$form->createView()]));
        return $reponse;
    }

    #[Route('/ajoutPrescri/{idper}', name: 'app_dossier_patient_ajout_prescri')]
    public function AjoutPrescription(PersonneRepository $per,$idper,Request $request,EntityManagerInterface $en): Response
    {
        $prescription = new Prescription();
        $form = $this->createForm(AjoutPrescriptionType::class,$prescription);
        $form->add('send',SubmitType::class,['label'=>'ajouter un prescription a se patient']);
        $form->handleRequest($request);


        if(($form->isSubmitted() && $form->isValid())){
            //form
            $appPres = new AppliquerPrescription();
            $appPres->setPatient($per->findOneById($idper))->setPrescription($prescription);
            $en->persist($appPres);
            $en->persist($prescription);
            $en->flush();


            //message
            $this->addFlash('info','ajout réussi');

            //prescription diagno
            //$per->AddPersoDiagno($idper,$prescription->getId());

            return $this->redirectToRoute('patient_list_patient');
        }

        $reponse = new Response($this->render('dossier_patient/PrescriptionForm.html.twig',['form'=>$form->createView()]));
        return $reponse;
    }
}
