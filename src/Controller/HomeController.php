<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Habib\DataTables\Builder\DataTableBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Materiel;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DataTableBuilderInterface $builder, EntityManagerInterface $entityManager): Response
    {
        $materiel = $entityManager->getRepository(Materiel::class)->findMoreThanOne();
        $tables = $builder->createDataTable('ListeDeMateriel');
        $tables->setOptions([
            'ajax' => [
                'url' => $this->generateUrl('app_ajaxData'),
                'dataSrc' => ''
            ],
            'columns' => [
                ['title' => 'Id'],
                ['title' => 'Nom'],
                ['title' => 'Prix Hors Taxe'],
                ['title' => 'Taux de TVA'],
                ['title' => 'Prix TTC'],
                ['title' => 'Quantité'],
                ['title' => 'Date de création'],
                ['title' => 'Action'],
            ],
        ]

        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tables' => $tables,
            'materiel'=> $materiel
        ]);
    }


    #[Route('/ajax/data', name: 'app_ajaxData')]
    public function AjaxData(EntityManagerInterface $entityManager): Response
    {
        $materiels = $entityManager->getRepository(Materiel::class)->findMoreThanOne();
        $data = [];
        foreach ($materiels as $item) {
           $data[] = [
                        $item->getId(),
                        $item->getNom(),
                        number_format($item->getPrixHt(),2 ,',',' ').'€',
                        $item->getTva()->getValeur()*100 ."%",
                        number_format($item->getPrixTtc(),2 ,',',' ').'€',
                        $item->getQuantite(),
                        date_format($item->getDateDeCreation()->setTimeZone(new \DateTimeZone('Europe/Paris')), "d/m/Y H:i:s"), 
                        sprintf('<a class="btn btn-light mx-1" data-bs-toggle="modal" data-bs-target="#Detail-%s-Modal"> Voir </a>',$item->getId()).
                        sprintf('<a class="btn btn-light mx-1" href="%s"> Modifier </a>',$this->generateUrl('app_materiel_mod', ['id' =>   $item->getId()])).
                        sprintf('<a class="btn btn-light mx-1" onclick=\'window.location.href="%s"\'> Décrémenter </a>',  $this->generateUrl('app_decrease_mat', ['id' =>   $item->getId()])),
                    ];
        }
        return $this->json($data);
    }
}
