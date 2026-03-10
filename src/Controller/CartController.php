<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(EntityManagerInterface $em): Response
    {
        $cartItems = $em->getRepository(CartItem::class)->findBy([
            'customer' => $this->getUser(),
        ]);

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }

    // clear cart
    #[Route('/panier/vider', name: 'app_cart_clear')]
    public function clear(EntityManagerInterface $em): Response
    {
        $cartItems = $em->getRepository(CartItem::class)->findBy([
            'customer' => $this->getUser(),
        ]);

        foreach ($cartItems as $item) {
            $em->remove($item);
        }

        $em->flush();
        return $this->redirectToRoute('app_cart');
    }

    // validate cart
    #[Route('/panier/valider', name: 'app_cart_validate')]
    public function validate(EntityManagerInterface $em): Response
    {
        $cartItems = $em->getRepository(CartItem::class)->findBy([
            'customer' => $this->getUser(),
        ]);

        // calculate total price
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        // create order
        $order = new Order();
        $order->setCustomer($this->getUser());
        $order->setTotalPrice($total);
        $order->setCreatedAt(new \DateTimeImmutable());
        $em->persist($order);

        // clear cart
        foreach ($cartItems as $item) {
            $em->remove($item);
        }

        $em->flush();
        return $this->render('cart/validate.html.twig');
    }
}
