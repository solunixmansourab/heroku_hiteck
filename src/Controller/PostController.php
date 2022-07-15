<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
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
class PostController extends AbstractController
{

    private $flasher;


    public function __construct(NotyfFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/posts", name="app_post_index", methods={"GET"})
     */
    public function allPosts(PostRepository $postRepository, PaginatorInterface $paginator, Request $request, PostCategoryRepository $postCategoryRepository): Response
    {
        $posts = $paginator->paginate($postRepository->findAll(), $request->query->getInt('page', 1), 15);
        $postCategories = $postCategoryRepository->findAll();

        return $this->render('admin/posts/posts.html.twig', [
            'posts' => $posts,
            'post_categories' => $postCategories,
        ]);
    }

    /**
     * @Route("/posts/new", name="app_post_new", methods={"GET", "POST"})
     */
    public function newPost(Request $request, PostRepository $postRepository, UploaderService $uploaderService): Response
    {
        $post = new Post();

        $user = $this->getUser();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($post->getContent());

            $post->setExcerpt(substr($post->getContent(), 0, 78));
            $post->setUser($user);

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('postImageFile')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderService->uploadPostImage($uploadedFile);

                $post->setPostImage($newFilename);
            }

            $postRepository->add($post, true);

            $this->flasher->addFlash('success', 'Article créé avec succès!!!');

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/posts/newPost.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="app_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/posts/edit/{slug}", name="app_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post, PostRepository $postRepository, UploaderService $uploaderService): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('postImageFile')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderService->uploadPostImage($uploadedFile);

                $post->setPostImage($newFilename);
            }

            $postRepository->add($post, true);

            $this->flasher->addFlash('success', 'Article édité avec succès!!!');
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/posts/editPost.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="app_post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getSlug(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
