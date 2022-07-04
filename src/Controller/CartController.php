<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/boutique/panier") // shop/cart
 */
class CartController extends AbstractController
{

    /**
     * @Route("/", name="app_shop_cart")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SessionInterface $session, ProductRepository $productRepository, CartService $cartService)
    {
        $panierWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();


        return $this->render('shop/cart.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="app_cart_add")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add($id, CartService $cartService, Request $request)
    {
        $cartService->add($id);

        //return $this->redirectToRoute('app_checkout');
        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * @Route("/decrease/{id}", name="app_cart_decrease")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function decrease($id, CartService $cartService)
    {
        $cartService->decrease($id);

        return $this->redirectToRoute('app_shop_cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="app_cart_remove")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove($id, CartService $cartService)
    {
        $cartService->remove($id);

        return $this->redirectToRoute('app_shop_cart');
    }
}