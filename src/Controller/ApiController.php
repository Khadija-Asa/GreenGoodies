<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function products(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $data = array_map(fn($product) => [
            'id'               => $product->getId(),
            'name'             => $product->getName(),
            'shortDescription' => $product->getDescription(),
            'fullDescription'  => $product->getLongDescription(),
            'price'            => $product->getPrice(),
            'picture'          => $product->getImage(),
        ], $products);

        // return json file
        return $this->json($data);
    }
}
