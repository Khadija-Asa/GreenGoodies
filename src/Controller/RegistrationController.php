<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // // verify password
            // $plainPassword = $form->get('plainPassword')->getData();
            // $confirmPassword = $form->get('confirmPassword')->getData();

            // if ($plainPassword !== $confirmPassword) {
            //     $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            // }

            // // verify CGU
            // $acceptedTerms = $form->get('acceptedTerms')->getData();
            // if (!$acceptedTerms) {
            //     $form->get('acceptedTerms')->addError(new FormError('Vous devez accepter les CGU.'));
            // }

            // if OK -> register
            // if ($form->isValid()) {
            //     $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            //     $user->setAcceptedTerms(true);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );
                $entityManager->persist($user);
                $entityManager->flush();

                // Connexion automatique après inscription
                return $security->login($user, LoginFormAuthenticator::class, 'main');
            }  

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}