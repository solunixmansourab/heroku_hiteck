<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Service\UploaderService;
use Flasher\Notyf\Prime\NotyfFactory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration")
 */
class ProductController extends AbstractController
{
    private $flasher;


    public function __construct(NotyfFactory $flasher) 
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/products", name="app_products_index", methods={"GET"})
     */
    public function allProducts(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request, ProductCategoryRepository $productCategoryRepository): Response
    {
        // Page uniquement accessible par l'administration
        
        $productCategories = $productCategoryRepository->findAll();

        $products = $paginator->paginate(
            $productRepository->findAll(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/products/products.html.twig', [
            'products' => $products,
            'productCategories' => $productCategories,
        ]);
    }

    /**
     * @Route("/products/new", name="app_products_new", methods={"GET", "POST"})
     */
    public function newProduct(Request $request, ProductRepository $productRepository, UploaderService $uploaderService): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('imageFilename')->getData();

            
            if ($uploadedFile) {
                $newFilename = $uploaderService->uploadProductImage($uploadedFile);

                $product->setCoverImage($newFilename);
            }

            /** @var UploadedFile $images */
            $images = $form->get('images')->getData();
            
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('galerie_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();
                
                $img->setFilename($fichier);
                $product->addImage($img);
            }

            $productRepository->add($product, true);
            
            $this->flasher->addFlash('success', 'Produit ajouté avec succès!!');
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/products/newProduct.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/products/{id}", name="app_product_show", methods={"GET"})
     */
    public function showProduct(Product $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/products/edit/{id}", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository, UploaderService $uploaderService): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('imageFilename')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderService->uploadProductImage($uploadedFile);

                $product->setCoverImage($newFilename);
            }

            $images = $form->get('images')->getData();

            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('galerie_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();

                $img->setFilename($fichier);
                $product->addImage($img);
            }

            $productRepository->add($product, true);

            $this->flasher->addFlash('success', 'Modifications éffectuées!!!');
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/products/editProduct.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/products/delete/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        $this->flasher->addFlash('warning', 'produit supprimé avec succès!!!');
        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
