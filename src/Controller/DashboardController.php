<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Avis;
use App\Repository\LivreRepository;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
	public function index(LivreRepository $livreRepository): Response
	{
		return $this->render('dashboard/index.html.twig', [
			'controller_name' => 'DashboardController',
			'livres' => $livreRepository->findAll(),
		]);
	}

	#[Route('/Livre/{id}', name: 'app_livre_for_user', methods: ['GET'])]
	public function show_livre(Livre $livre, AvisRepository $avisRepository): Response
	{
		// Récupérez les avis associés au livre
		$avis = $livre->getAvis();
		

		return $this->render('dashboard/livre_for_user.html.twig', [
			'livre' => $livre,
			'avis' => $avis,
		]);
	}
}
