<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\LinkUser;
use App\Form\ReadmeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;

use function PHPUnit\Framework\throwException;

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
     * @Route("/@{username}", name="app_view_readme")
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
     * @Route("/readme/add/", name="app_readme_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
            return $this->redirectToRoute('app_readme');
        }

        $link = new LinkUser();
        $form = $this->createForm(ReadmeFormType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setUser($user);

            $entityManager->persist($link);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('readme.add.success'));
        }

        return $this->render('readme/form.html.twig', [
            'title' => new TranslatableMessage('readme.add.title'),
            'readmeForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/readme/edit/{id}", name="app_readme_edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(LinkUser::class);
        $link = $repository->find($id);

        $form = $this->createForm(ReadmeFormType::class, $link);
        $form->handleRequest($request);

        if ($user->getId() !== $link->getUser()->getId()) {
            $this->addFlash('warning', new TranslatableMessage("main.error"));
        } 
        else {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($link);
                $entityManager->flush();

                $this->addFlash('success', new TranslatableMessage('readme.edit.success'));
            }
        }

        return $this->render('readme/form.html.twig', [
            'title' => new TranslatableMessage('readme.edit.title'),
            'readmeForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/readme/delete/{id}", name="app_readme_delete")
     */
    public function delete(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
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

            $this->addFlash('success', new TranslatableMessage('readme.delete.success'));
        }

        return $this->redirectToRoute('app_readme');
    }
}
