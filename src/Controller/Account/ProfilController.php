<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-compte/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("", name="app_profil_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user) {
            $this->addFlash('info', 'Vous devez vous connecté pour acceder à cette page');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/profil/index.html.twig', compact('user'));
    }

    /**
     * @Route("/modifier/{id}", name="app_profil_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_profil_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="app_profil_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_profil_index', [], Response::HTTP_SEE_OTHER);
    }
}
