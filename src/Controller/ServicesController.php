<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services", name="app_services")
     */
    public function index(): Response
    {
        return $this->render('services/services_page.html.twig');
    }

    /**
     * @Route("/services/detail", name="app_service_detail")
     */
    public function show(): Response
    {
        return $this->render('services/service_single.html.twig');
    }
}
