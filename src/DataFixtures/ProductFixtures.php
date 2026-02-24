<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'image' => 'products/p1.jpg',
                'name' => 'Kit d\hygiène recyclable',
                'description' => 'Pour une salle de bain éco-friendly',
                'price' => 24.99,
            ],
            [
                'image' => 'products/p2.jpg',
                'name' => 'Shot Tropical',
                'description' => 'Fruits frais, pressés à froid',
                'price' => 4.50,
            ],
            [
                'image' => 'products/p3.jpg',
                'name' => 'Gourde en bois',
                'description' => '50cl, bois d\olivier',
                'price' => 16.90,
            ],
            [
                'image' => 'products/p4.jpg',
                'name' => 'Disques Démaquillants x3',
                'description' => 'Solution efficace pour vous démaquiller en douceur',
                'price' => 19.90,
            ],
            [
                'image' => 'products/p5.jpg',
                'name' => 'Bougie Lavande & Patchouli',
                'description' => 'Cire naturelle',
                'price' => 32,
            ],
            [
                'image' => 'products/p6.jpg',
                'name' => 'Brosse à dent',
                'description' => 'Bois de hêtre rouge issu de forêts gérées durablement',
                'price' => 5.40,
            ],
            [
                'image' => 'products/p7.jpg',
                'name' => 'Kit couvert en bois',
                'description' => 'Revêtement Bio en olivier & sac de transport',
                'price' => 12.30,
            ],
            [
                'image' => 'products/p8.jpg',
                'name' => 'Nécessaire, déodorant Bio',
                'description' => '50ml déodorant à l\eucalyptus',
                'price' => 8.50,
            ],
            [
                'image' => 'products/p9.jpg',
                'name' => 'Savon Bio',
                'description' => 'Thé, Orange & Girofle',
                'price' => 18.90,
            ],  
        ];

        // Création des produits
        foreach ($productsData as $data) {
            $product = new Product();
            $product->setImage($data['image']);
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
