<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Flasher\Notyf\Prime\NotyfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/products/categories")
 */
class ProductCategoryController extends AbstractController
{

    private $flasher;


    public function __construct(NotyfFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/", name="app_product_category_index", methods={"GET"})
     */
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        $productCategories = $productCategoryRepository->findAll();

        return $this->render('admin/product_categories/product_categories_index.html.twig', compact('productCategories'));
    }

    /**
     * @Route("/new", name="app_product_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductCategoryRepository $productCategoryRepository): Response
    {
        $productCategory = new ProductCategory();

        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productCategoryRepository->add($productCategory, true);

            $this->flasher->addFlash('success', 'Catégorie ajouté avec succès!!!');

            return $this->redirectToRoute('app_product_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product_categories/product_category_new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_category_show", methods={"GET"})
     */
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('product_category/show.html.twig', compact('productCategory'));
    }

    /**
     * @Route("/edit/{id}", name="app_product_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ProductCategory $productCategory, ProductCategoryRepository $productCategoryRepository): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productCategoryRepository->add($productCategory, true);

            $this->flasher->addFlash('success', 'Catégorie éditée avec succès!!!');

            return $this->redirectToRoute('app_product_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product_categories/product_category_edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_category_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductCategory $productCategory, ProductCategoryRepository $productCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->request->get('_token'))) {
            $productCategoryRepository->remove($productCategory, true);
        }

        $this->flasher->addFlash('warning', 'Catégorie supprimée avec succès!!!');

        return $this->redirectToRoute('app_product_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
