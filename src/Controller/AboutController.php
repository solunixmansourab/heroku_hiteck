<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{

    /**
     * @Route("/a-propos/notre-société", name="app_about")
     */
    public function index(): Response
    {
        return $this->render('about/about_page.html.twig');
    }

    /**
     * @Route("/a-propos/notre-mission", name="app_about_mission")
     */
    public function mission(): Response
    {
        return $this->render('about/mission_page.html.twig');
    }

}
