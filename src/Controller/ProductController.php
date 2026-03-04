<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\CartItemType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        // get all products on data base
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_product_show')]
    public function show(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = null;
        $cartItem = null;

        if ($this->getUser()) {
            // product already on cart ?
            $cartItem = $em->getRepository(CartItem::class)->findOneBy([
                'customer' => $this->getUser(),
                'product'  => $product,
            ]);

            $data = $cartItem ?? new CartItem();
            $form = $this->createForm(CartItemType::class, $data);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $quantity = $form->get('quantity')->getData();

                if ($quantity <= 0) {
                    if ($cartItem) {
                        $em->remove($cartItem);
                    }
                } else {
                    if (!$cartItem) {
                        $cartItem = new CartItem();
                        $cartItem->setCustomer($this->getUser());
                        $cartItem->setProduct($product);
                    }
                    $cartItem->setQuantity($quantity);
                    $em->persist($cartItem);
                }

                $em->flush();
                return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
            }
        }

        return $this->render('product/show.html.twig', [
            'product'  => $product,
            'form'     => $form?->createView(),
            'cartItem' => $cartItem,
        ]);
    }
}
