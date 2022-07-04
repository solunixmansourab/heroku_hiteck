<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminIndexController extends AbstractController
{

    /**
     * @Route("/administration", name="app_admin")
     */
    public function index() : Response
    {
        $user = $this->getUser();

        return $this->render('admin/index/index_page.html.twig', [
            'user' => $user
        ]);
    }
}