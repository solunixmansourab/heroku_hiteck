<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Flasher\Notyf\Prime\NotyfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-compte/mes-adresses")
 */
class AddressController extends AbstractController
{

    private $flasher;


    public function __construct(NotyfFactory $flasher) {
        $this->flasher = $flasher;
    }

    /**
     * @Route("", name="app_address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->flasher->addFlash('info', 'Vous devez vous connecter pour acceder à cette page!');
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/nouvelle-adresse", name="app_address_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($form->get('firstName')->getData() )) {
                $address->setFirstName($user->getFirstName());
            }

            if (empty($form->get('lastName')->getData())) {
                $address->setLastName($user->getLastName());
            }

            if (empty($form->get('email')->getData())) {
                $address->setEmail($user->getEmail());
            }

            $address->setUser($user);
            $adresse = $address->getAdresse();
            $addressRepository->add($address, true);

            $this->addFlash('success', 'Adresse: '. $adresse .' a été ajouté avec succès!');
            return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/address/new.html.twig', [
            'address' => $address,
            'form' => $form,
            'user' => $user,

        ]);
    }

    /**
     * @Route("/{id}", name="app_address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="app_address_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->add($address, true);

            return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="app_address_delete", methods={"POST"})
     */
    public function delete(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $addressRepository->remove($address, true);
        }

        return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
    }
}
