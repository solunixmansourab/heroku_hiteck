<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Form\SearchForm;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{

    /**
     * @Route("/boutique", name="app_shop")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $data = new SearchService();
        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $products = $productRepository->findSearch($data);

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/boutique/produit/{slug}", name="app_shop_detail")
     */
    public function detail(Product $product, CartService $cartService): Response
    {
        $panierWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();

        foreach ($panierWithData as $key  => $value) {
            $item = $value;
        }

        return $this->render('shop/detail.html.twig', [
            'product' => $product,
            //'item' => $item,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/boutique/verifier-commande", name="app_shop_checkout") //shop/checkout
     */
    public function checkoutPage(CartService $cartService, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $address = new Address();

        $addresses = $user->getAddresses()->getValues();

        $form = $this->createFormBuilder($address)
            ->add('adresse', EntityType::class, [
                'class' => Address::class,
                'expanded' => true,
            ])
            ->getForm()
        ;

        $panierWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();

        $order = new Order();

        $order->setCustomer($user);
        $order->setCreatedAt(new \DateTime());
        $order->setOrderPrice($total);

        $manager->persist($order);

        foreach ($cartService->getFullCart() as $product ) {
            $orderDetails = new OrderDetails();

            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getTitle());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

            $manager->persist($orderDetails);
        }

        $manager->flush();

        return $this->render('shop/checkout.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add-to-cart/{id}", name="shop_add_cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add($id, CartService $cartService, Request $request)
    {
        $cartService->add($id);

        //return $this->redirectToRoute('app_checkout');
        return $this->redirectToRoute('app_shop');
    }

}
