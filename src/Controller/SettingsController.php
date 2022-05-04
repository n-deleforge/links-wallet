<?php

namespace App\Controller;

use App\Form\EmailFormType;
use App\Form\NameFormType;
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
     * @Route("/settings/modify/name", name="app_modify_name")
     */
    public function modifyName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(NameFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.modifyName.success'));
        }

        return $this->render('settings/modifyName.html.twig', [
            'nameForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/modify/email", name="app_modify_email")
     */
    public function modifyEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(EmailFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.modifyEmail.success'));
        }

        return $this->render('settings/modifyEmail.html.twig', [
            'emailForm' => $form->createView(),
            'user' => $user
        ]);
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