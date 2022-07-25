<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Notyf\Prime\NotyfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer, NotyfFactory $flasher): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('hello@exemple.com')
                ->subject($contact->getSubject())
                ->text($contact->getMessage())
            ;

            $mailer->send($email);

            $flasher->addFlash('success', 'Votre message à été envoyé!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/contact_page.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
