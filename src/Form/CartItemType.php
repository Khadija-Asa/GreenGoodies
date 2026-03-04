<?php

namespace App\Form;

use App\Entity\CartItem;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('quantity', IntegerType::class, [
            'label' => 'Quantité',
            'attr'  => ['min' => 0],
        ])
    ;
}
}
