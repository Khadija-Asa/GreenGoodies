<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface as SerializerSerializerInterface;

final class ApiController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function products(
        ProductRepository $productRepository,
        SerializerSerializerInterface $serializer
    ): JsonResponse
    {
        $products = $productRepository->findAll();

        $json = $serializer->serialize($products, 'json', [
            'groups' => ['goodies']
        ]);

        // return json file
        return new JsonResponse($json, 200, [], true);
    }
}
