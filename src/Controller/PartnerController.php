<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use App\Service\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/partners")
 */
class PartnerController extends AbstractController
{
    /**
     * @Route("/", name="app_partner_index", methods={"GET"})
     */
    public function index(PartnerRepository $partnerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $partners = $paginator->paginate(
            $partnerRepository->findAll(),
            $request->query->getInt('page', 1), 5)
        ;

        return $this->render('admin/partners/partners.html.twig', [
            'partners' => $partners,
        ]);
    }

    /**
     * @Route("/new", name="app_partner_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PartnerRepository $partnerRepository, ImageUploader $imageUploader): Response
    {
        $partner = new Partner();

        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('imageFile')->getData();

            if ($uploadedImage) {
                $imageFile = $imageUploader->upload($uploadedImage);

                $partner->setImage($imageFile);
            }

            $partnerRepository->add($partner, true);

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/new.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_partner_show", methods={"GET"})
     */
    public function show(Partner $partner): Response
    {
        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_partner_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Partner $partner, PartnerRepository $partnerRepository): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerRepository->add($partner, true);

            return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_partner_delete", methods={"POST"})
     */
    public function delete(Request $request, Partner $partner, PartnerRepository $partnerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partner->getId(), $request->request->get('_token'))) {
            $partnerRepository->remove($partner, true);
        }

        return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
    }
}
