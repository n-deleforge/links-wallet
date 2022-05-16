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
class AccountController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/update/name", name="app_account_updateName")
     */
    public function updateName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("account.updateName.notVerified"));
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(NameFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('account.updateName.success'));
        }

        return $this->render('account/updateName.html.twig', [
            'nameForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/account/update/email", name="app_account_updateEmail")
     */
    public function updateEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("account.updateEmail.notVerified"));
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(EmailFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('account.updateEmail.success'));
        }

        return $this->render('account/updateEmail.html.twig', [
            'emailForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/account/update/password", name="app_account_updatePassword")
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher ,EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("account.updatePassword.notVerified"));
            return $this->redirectToRoute('app_account');
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

            $this->addFlash('success', new TranslatableMessage('account.updatePassword.success'));
        }

        return $this->render('account/updatePassword.html.twig', [
            'passwordForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/account/delete", name="app_account_delete")
     */
    public function deleteAccount(): Response
    {
        $this->addFlash('warning', new TranslatableMessage("main.error"));
        return $this->redirectToRoute('app_account');
    }
}