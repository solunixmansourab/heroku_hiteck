<?php

namespace App\Controller\Account;


use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Notyf\Prime\NotyfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-compte")
 */
class AccountController extends AbstractController
{

    private $flasher;

    public function __construct(NotyfFactory $flasher)
    {
        $this->flasher = $flasher;
       
    }

    /**
     * @Route("", name="app_account")
     */
    public function indexPage()
    {
        $user = $this->getUser();

        if (!$user) {
            $this->flasher->addFlash('info', 'Vous devez vous connecter pour acceder à cette page.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/index.html.twig', compact('user'));
    }

    /**
     * @Route("/changer-mot-de-passe", name="app_change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current_password = $form->get('current_password')->getData();

            if ($hasher->isPasswordValid($user, $current_password)) {
                $new_password = $form->get('new_password')->getData();

                $password = $hasher->hashPassword($user, $new_password);

                $user->setPassword($password);

                $manager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succes');

                return $this->redirectToRoute('app_account');
            } else {
                $this->addFlash('danger', "Votre mot de passe n'est pas le bon");
            }
        }

        return $this->render('account/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/reinitialiser-mot-de-passe", name="app_account_reset_password")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resetPassword()
    {
        return $this->render('account/reset_password.html.twig');
    }

}