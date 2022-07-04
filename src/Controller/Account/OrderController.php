<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Flasher\Notyf\Prime\NotyfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-compte/mes-commandes")
 */
class OrderController extends AbstractController
{

    private $flasher;

    public function __construct(NotyfFactory $flasher) {
        $this->flasher = $flasher;
    }

    /**
     * @Route("", name="app_order_index", methods={"GET", "POST"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->flasher->addFlash('info', 'Vous devez vous connecter pour acceder à cette page!');
            
            return $this->redirectToRoute('app_login');
        }

        $orders = $orderRepository->findAll();

        return $this->render('account/order/order_list.html.twig', [
            'orders' => $orders,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/nouvelle-commande", name="app_order_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        $this->addFlash('success', 'Votre commande a été créée');

        return $this->redirectToRoute('app_order_index');
    }

    /**
     * @Route("/details-de-la-commande", name="app_order_show", methods={"GET"})
     */
    public function show(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findOrdersByUser();

        foreach ($orders as $order) {
            $details = $order->getOrderDetails();
        }

        return $this->render('account/order/order_details.html.twig', [
            'user' => $user,
            'details' => $details,
        ]);
    }


    /**
     * @Route("/supprimer-la-commande/{id}", name="app_order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
