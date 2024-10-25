<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TVA;
use App\Form\TvaType;
use Doctrine\ORM\EntityManagerInterface;

class TVAController extends AbstractController
{
    #[Route('/t/v/a', name: 'app_t_v_a_add')]
    public function AddTva(Request $request,EntityManagerInterface $entityManager): Response
    {
        $tva = new TVA();
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tva);
            $entityManager->flush();
            return $this->redirectToRoute('app_home'); // Redirection après soumission
        }
        return $this->render('tva/_add.html.twig', [
            'controller_name' => 'TVAController',
            'form' => $form->createView(),
        ]);
    }
}
