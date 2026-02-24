<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    // show products
    #[Route('/produit/{id}', name: 'app_product_show')]
    public function show(int $id): Response
    {
        return $this->render('product/show.html.twig', [
            'id' => $id,
        ]);
    }
}
