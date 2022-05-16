<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\LinkUser;
use App\Form\ModelFormType;
use App\Form\ReadmeSettingsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;

class ReadmeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/readme", name="app_readme")
     */
    public function index(): Response
    {
        return $this->render('readme/index.html.twig');
    }

    /**
     * @Route("/@{username}", name="app_readme_view")
     */
    public function view(ManagerRegistry $doctrine, $username): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->findByUsername($username);

        if (!$user) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
            return $this->redirectToRoute('app_home');
        }

        return $this->render('readme/view.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/readme/model/add/", name="app_readme_addModel")
     */
    public function addModel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
            return $this->redirectToRoute('app_readme');
        }

        $link = new LinkUser();
        $form = $this->createForm(ModelFormType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setUser($user);

            $entityManager->persist($link);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('readme.modelForm.add.success'));
        }

        return $this->render('readme/modelForm.html.twig', [
            'title' => new TranslatableMessage('readme.modelForm.add.title'),
            'submit' => new TranslatableMessage('readme.modelForm.add.submit'),
            'modelForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/readme/model/update/{id}", name="app_readme_updateModel")
     */
    public function updateModel(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(LinkUser::class);
        $link = $repository->find($id);

        $form = $this->createForm(ModelFormType::class, $link);
        $form->handleRequest($request);

        if ($user->getId() !== $link->getUser()->getId()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
        } 
        else {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($link);
                $entityManager->flush();

                $this->addFlash('success', new TranslatableMessage('readme.modelForm.update.success'));
            }
        }

        return $this->render('readme/modelForm.html.twig', [
            'title' => new TranslatableMessage('readme.modelForm.update.title'),
            'submit' => new TranslatableMessage('readme.modelForm.update.submit'),
            'modelForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/readme/model/delete/{id}", name="app_readme_deleteModel")
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

            $this->addFlash('success', new TranslatableMessage('readme.modelForm.delete.success'));
        }

        return $this->redirectToRoute('app_readme');
    }

    /**
     * @Route("/readme/settings", name="app_readme_settings")
     */
    public function updateSettings(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(ReadmeSettingsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('readme.settings.success'));
        }

        return $this->render('readme/settings.html.twig', [
            'settingsForm' => $form->createView()
        ]);
    }
}
