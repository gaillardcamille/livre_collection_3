<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\User;
use App\Repository\LivreRepository;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
		$avis = $livre->getAvis();
		
		return $this->render('dashboard/livre_for_user.html.twig', [
			'livre' => $livre,
			'avis' => $avis,
		]);
	}

    #[Route('/new/{livreId}/{userId}', name: 'app_ajouter_avis', methods: ['GET', 'POST'])]
    public function add_avis(int $livreId, int $userId, Request $request, EntityManagerInterface $entityManager, LivreRepository $livreRepository, UserRepository $userRepository): Response
    {
        $livre = $livreRepository->find($livreId);
        
        $user = $userRepository->find($userId);
    
        $avis = new Avis();
        $avis->setLivre($livre);
        $avis->setUser($user); 
    
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avis);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_dashboard');
        }
    
        return $this->render('dashboard/ajouter_avis.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
