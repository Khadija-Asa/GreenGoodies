<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    // show account
    #[Route('/mon-compte', name: 'app_account')]
    public function index(EntityManagerInterface $em): Response
    {
        $orders = $em->getRepository(Order::class)->findBy(
            ['customer' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    // delete account
    #[Route('/mon-compte/supprimer', name: 'app_account_delete')]
    public function delete(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // delete cartItems
        $cartItems = $em->getRepository(CartItem::class)->findBy(['customer' => $user]);
        foreach ($cartItems as $item) {
            $em->remove($item);
        }

        $this->container->get('security.token_storage')->setToken(null);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    // API access
    #[Route('/mon-compte/api-access', name: 'app_account_api_toggle')]
    public function toggleApi(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $user->setApiAccess(!$user->isApiAccess());
        $em->flush();
        return $this->redirectToRoute('app_account');
    }
}
