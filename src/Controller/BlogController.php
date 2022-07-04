<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $paginator->paginate($postRepository->findAll(), $request->query->getInt('page', 1), 6);

        return $this->render('blog/blog_page.html.twig', compact('posts'));
    }

    /**
     * @Route("/blog/article/{slug}", name="app_blog_detail")
     */
    public function detail(Post $post, PostCategoryRepository $postCategoryRepository, PostRepository $postRepository): Response
    {
        $categories = $postCategoryRepository->findAll();

        $recents = $postRepository->findPostRecent();

        return $this->render('blog/blog_detail.html.twig', [
            'post' => $post,
            'categories' => $categories,
            'recents' => $recents,
        ]);
    }
}
