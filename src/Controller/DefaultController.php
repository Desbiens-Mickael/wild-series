<?php 

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/', name: 'home_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        ProgramRepository $programRepository, 
        CategoryRepository $categoryRepository,
        ActorRepository $actorRepository,
        ): Response
    {
        $programs = $programRepository->findAll();
        $categories = $categoryRepository->findAll();
        $actors = $actorRepository->findAll();
        return $this->render('home/index.html.twig', [
            'programs' => $programs,
            'categories' => $categories,
            'actors' => $actors,
        ]);
    }
}