<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ProductRepository
     */
    protected $productRepository;


    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $id
     */
    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id] ++;
        }else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    /**
     * @param int $id
     */
    public function decrease(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id] --;
        }else {
            $panier[$id] = 1;
        }

//        $panier = $this->session->get('panier', []);
//
//        if (!empty($panier[$id])) {
//            $panier[$id] --;
//        }elseif ($panier[$id] < 1) {
//            unset($panier[$id]);
//        } else {
//            $panier[$id] = 1;
//        }

        $this->session->set('panier', $panier);
    }

    /**
     * @param int $id
     */
    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);

            $this->session->set('panier', $panier);
        }
    }

    public function get()
    {
        $panier = $this->session->get('panier');

        return $panier;
    }

    /**
     * @return array
     */
    public function getFullCart() : array
    {
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

    public function getTotal()
    {
        $total = 0;

        $panierWithData = $this->getFullCart();

        foreach ($panierWithData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $total;
    }
}