<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\PostCategoryType;
use App\Repository\PostCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/posts/categories")
 */
class PostCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_post_category_index", methods={"GET"})
     */
    public function index(PostCategoryRepository $postCategoryRepository): Response
    {
        $postCategories = $postCategoryRepository->findAll();

        return $this->render('admin/post_categories/post_categories_index.html.twig', compact('postCategories'));
    }

    /**
     * @Route("/new", name="app_post_category_new", methods={"GET", "POST"})
     */
    public function newCategory(Request $request, PostCategoryRepository $postCategoryRepository): Response
    {
        $postCategory = new PostCategory();

        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postCategoryRepository->add($postCategory, true);

            return $this->redirectToRoute('app_post_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post_categories/post_category_new.html.twig', [
            'post_category' => $postCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_post_category_show", methods={"GET"})
     */
    public function showCategory(PostCategory $postCategory): Response
    {
        return $this->render('post_categories/show.html.twig', [
            'post_category' => $postCategory,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_post_category_edit", methods={"GET", "POST"})
     */
    public function editCategory(Request $request, PostCategory $postCategory, PostCategoryRepository $postCategoryRepository): Response
    {
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postCategoryRepository->add($postCategory, true);

            return $this->redirectToRoute('app_post_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post_categories/post_category_edit.html.twig', [
            'post_category' => $postCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_post_category_delete", methods={"POST"})
     */
    public function deleteCategory(Request $request, PostCategory $postCategory, PostCategoryRepository $postCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postCategory->getId(), $request->request->get('_token'))) {
            $postCategoryRepository->remove($postCategory, true);
        }

        return $this->redirectToRoute('app_post_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
