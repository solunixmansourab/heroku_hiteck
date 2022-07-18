<?php

namespace App\Controller;

use App\Repository\PartnerRepository;
use App\Repository\PostRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $postRepository, PartnerRepository $partnerRepository, ServiceRepository $serviceRepository): Response
    {
        $latest = $postRepository->findPostRecent();
        $partners = $partnerRepository->findAll();

        $services = $serviceRepository->getHomeServices();

        return $this->render('home/homepage.html.twig', [
            'latest' => $latest,
            'partners' => $partners,
            'services' => $services,
        ]);
    }
}
