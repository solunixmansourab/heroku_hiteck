<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services", name="app_services")
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('services/services_page.html.twig', compact('services'));
    }

    /**
     * @Route("/services/details/{slug}", name="app_service_detail")
     */
    public function show(Service $service): Response
    {
        return $this->render('services/service_single.html.twig', compact('service'));
    }
}
