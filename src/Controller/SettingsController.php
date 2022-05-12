<?php

namespace App\Controller;

use App\Form\EmailFormType;
use App\Form\NameFormType;
use App\Form\PasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;


/**
 * @isGranted("IS_AUTHENTICATED_FULLY")
 */
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
     * @Route("/settings/update/name", name="app_settings_updateName")
     */
    public function updateName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.updateName.notVerified"));
            return $this->redirectToRoute('app_settings');
        }

        $form = $this->createForm(NameFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.updateName.success'));
        }

        return $this->render('settings/updateName.html.twig', [
            'nameForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/update/email", name="app_settings_updateEmail")
     */
    public function updateEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.updateEmail.notVerified"));
            return $this->redirectToRoute('app_settings');
        }

        $form = $this->createForm(EmailFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.updateEmail.success'));
        }

        return $this->render('settings/updateEmail.html.twig', [
            'emailForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/update/password", name="app_settings_updatePassword")
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher ,EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.updatePassword.notVerified"));
            return $this->redirectToRoute('app_settings');
        }

        $form = $this->createForm(PasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.updatePassword.success'));
        }

        return $this->render('settings/updatePassword.html.twig', [
            'passwordForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/deleteAccount", name="app_settings_deleteAccount")
     */
    public function deleteAccount(): Response
    {
        $this->addFlash('warning', new TranslatableMessage("main.error"));
        return $this->redirectToRoute('app_settings');
    }
}