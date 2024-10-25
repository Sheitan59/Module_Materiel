<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Materiel;
use App\Form\MaterielType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\EntityManagerInterface;
use FPDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Filesystem\Filesystem;


class MaterielController extends AbstractController
{
    #[Route('/materiel/add', name: 'app_materiel_add')]
    public function AddMateriel(Request $request,EntityManagerInterface $entityManager): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($materiel);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('materiel/_add.html.twig', [
            'controller_name' => 'MaterielController',
            'form' => $form->createView(),
        ]);
    }
    #[Route('/materiel/modifier/{id}', name: 'app_materiel_mod')]
    public function ModMateriel(Request $request,EntityManagerInterface $entityManager, Materiel $id): Response
    {
        $form = $this->createForm(MaterielType::class, $id);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($id);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('materiel/_modifier.html.twig', [
            'controller_name' => 'MaterielController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/materiel/decrease/confirm/{id}', name: 'app_decrease_mat')]
    public function decrease(Request $request,MailerInterface $mailer,EntityManagerInterface $entityManager, Materiel $id): Response
    {
        $newQ = (($id->getQuantite()) - 1);
        $id->setQuantite($newQ);
        $entityManager->persist($id);
        $entityManager->flush();
        if ($id->getQuantite() < 1) {
            $email = (new Email())
            ->from($this->getParameter('SenderMail'))
            ->to($this->getParameter('ReceiverMail'))
            ->subject('Quantité Materiel')
            ->text('Bonjour
             Nous vous informons que le materiel numero : ' .$id->getId().'  '. $id->getNom() .'est en rupture. 
              Veuillez prendre contact avec le gestionnaire afin d\'en augmenter la quantité .
             A bientot');
            try{   
              $mailer->send($email);
              return $this->redirectToRoute('app_home');
           }
           catch (\Exception $e){
            $this->logger->alert(sprintf('Votre Email n\'a pu être envoyé %s' , $e)) ;
           }   
        } 
        return $this->redirectToRoute('app_home');
    }

    #[Route('/materiel/pdf/{id}', name: 'app_to_pdf')]
    public function PdfMateriel(Request $request,EntityManagerInterface $entityManager, Materiel $id): Response
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->mkdir('/trash');
            $pdf = new FPDF();
            $pdf->AddPage(); 
            $pdf->SetFont('Arial', 'B', '15'); 
            $pdf->SetTextColor(0,0,0);
            $pdf->SetXY(10,10);
            $pdf->Write(15, sprintf( "ID : %s",$id->getId()));
            $pdf->SetXY(10, 35);
            $pdf->Write(15, sprintf("Nom : %s",$id->getNom()));
            $pdf->SetXY(10, 55);
            $pdf->Write(15,  sprintf("Prix HT : %s", $id->getPrixHt()));
            $pdf->SetXY(10, 80);
            $pdf->Write(15,  sprintf("TVA : %s" ,$id->getTVA()->getLibelle()));
            $pdf->SetXY(10, 105);
            $pdf->Write(15,  sprintf("Prix TTC : %s " ,$id->getPrixTtc()));
            $pdf->SetXY(10, 130);
            $pdf->Write(15,  utf8_decode(sprintf("Quantité : %s" , $id->getQuantite())));
            $pdf->SetXY(10, 155);
            $pdf->Write(15,  sprintf("Date de creation : %s" ,date_format($id->getDateDeCreation()->setTimeZone(new \DateTimeZone('Europe/Paris')), "d/m/Y H:i:s")));
            $pdf->Output('F','trash/'.$id->getNom().'.pdf');
            return $this->file('trash/'.$id->getNom().'.pdf');
            } catch (\Exception $e){
                $this->logger->alert(sprintf('Pdf non généré %s' , $e)) ;
               }   
            return $this->redirectToRoute('app_home');
    }
}
