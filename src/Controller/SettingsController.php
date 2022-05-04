<?php

namespace App\Controller;

use App\Form\NameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;

class SettingsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/settings", name="app_settings")
     */
    public function index(): Response
    {
        return $this->render('settings/index.html.twig');
    }

    /**
     * @Route("/settings/modify/username", name="app_modify_username")
     */
    public function modifyUsername(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(NameType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.modifyUsername.success'));
        }

        return $this->render('settings/modifyUsername.html.twig', [
            'nameForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/modify/email", name="app_modify_email")
     */
    public function modifyEmail(): Response
    {
        return $this->render('settings/index.html.twig');
    }

    /**
     * @Route("/settings/modify/password", name="app_modify_password")
     */
    public function modifyPassword(): Response
    {
        return $this->render('settings/index.html.twig');
    }

    /**
     * @Route("/settings/delete", name="app_delete_account")
     */
    public function deleteAccount(): Response
    {
        return $this->render('settings/index.html.twig');
    }
}