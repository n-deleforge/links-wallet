<?php

namespace App\Controller;

use App\Entity\LinkUser;
use App\Form\ReadmeModelType;
use App\Form\ReadmePersonalizationType;
use App\Form\SettingsEmailType;
use App\Form\SettingsNameType;
use App\Form\SettingsPasswordType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
     * @Route("/settings/account/name", name="app_settings_update_name")
     */
    public function updateName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.account.name.notVerified"));
            return $this->redirectToRoute('app_settings');
        }

        $form = $this->createForm(SettingsNameType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.account.name.success'));
        }

        return $this->render('settings/account/name.html.twig', [
            'nameForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/account/email", name="app_settings_update_email")
     */
    public function updateEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.account.email.notVerified"));
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(SettingsEmailType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.account.email.success'));
        }

        return $this->render('settings/account/email.html.twig', [
            'emailForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/settings/account/password", name="app_settings_update_password")
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher ,EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("settings.account.password.notVerified"));
            return $this->redirectToRoute('app_account');
        }

        $form = $this->createForm(SettingsPasswordType::class, $user);
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

            $this->addFlash('success', new TranslatableMessage('settings.account.password.success'));
        }

        return $this->render('settings/account/password.html.twig', [
            'passwordForm' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/settings/readme/add-model/", name="app_settings_add_model")
     */
    public function addModel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
            return $this->redirectToRoute('app_readme');
        }

        $link = new LinkUser();
        $form = $this->createForm(ReadmeModelType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setUser($user);

            $entityManager->persist($link);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.readme.model.add.success'));
        }

        return $this->render('settings/readme/model.html.twig', [
            'title' => new TranslatableMessage('settings.readme.model.add.title'),
            'submit' => new TranslatableMessage('settings.readme.model.add.submit'),
            'modelForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/settings/readme/update-model/{id}", name="app_settings_update_model")
     */
    public function updateModel(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(LinkUser::class);
        $link = $repository->find($id);

        $form = $this->createForm(ReadmeModelType::class, $link);
        $form->handleRequest($request);

        if ($user->getId() !== $link->getUser()->getId()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
        } 
        else {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($link);
                $entityManager->flush();

                $this->addFlash('success', new TranslatableMessage('settings.readme.model.update.success'));
            }
        }

        return $this->render('settings/readme/model.html.twig', [
            'title' => new TranslatableMessage('settings.readme.model.update.title'),
            'submit' => new TranslatableMessage('settings.readme.model.update.submit'),
            'modelForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/settings/readme/delete-model/{id}", name="app_settings_delete_model")
     */
    public function deleteModel(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(LinkUser::class);
        $link = $repository->find($id);

        if ($user->getId() !== $link->getUser()->getId()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
        } 
        else {
            $entityManager->remove($link);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.readme.model.delete.success'));
        }

        return $this->redirectToRoute('app_settings');
    }

    /**
     * @Route("/settings/readme/personalization", name="app_settings_personalization")
     */
    public function updatePersonalization(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(ReadmePersonalizationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();

            if ($avatar) {
                $avatarFile = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFile);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('settings.readme.personalization.success'));
        }

        return $this->render('settings/readme/personalization.html.twig', [
            'settingsForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/settings/readme/personalization/delete-avatar", name="app_settings_personalization_delete_avatar")
     */
    public function deleteAvatar(EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $user->setAvatar("");

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', new TranslatableMessage('settings.readme.personalization.avatar.deleted'));

        return $this->redirectToRoute('app_settings_personalization');
    }
}