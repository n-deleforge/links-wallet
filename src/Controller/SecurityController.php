<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(
     *     "/login",
     *     name="app_login"
     * )
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(LoginFormType::class);
        $lastEmail = $authenticationUtils->getLastUsername();
        $loginError = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'lastEmail' => $lastEmail,
            'loginError' => $loginError
        ]);
    }

    /**
     * @Route(
     *      "/logout",
     *      name="app_logout"
     * )
     */
    public function logout(): void
    {
        throw new \LogicException();
    }
}
