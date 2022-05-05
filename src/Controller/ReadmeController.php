<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\ReadmeFormType;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;

use function Symfony\Component\String\s;

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
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(Link::class);
        $links = $repository->findAllForOneUser($user);

        return $this->render('readme/index.html.twig', [
            'links' => $links
        ]);
    }

    /**
     * @Route("/readme/view/{username}", name="app_view_readme")
     */
    public function view($username): Response
    {
        return $this->render('readme/view.html.twig', [
            'username' => $username
        ]);
    }

    /**
     * @Route("/readme/add/", name="app_readme_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $link = new Link();
        $form = $this->createForm(ReadmeFormType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setUser($user);

            $entityManager->persist($link);
            $entityManager->flush();

            $this->addFlash('success', new TranslatableMessage('readme.add.success'));
        }

        return $this->render('readme/add.html.twig', [
            'readmeAddForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/readme/delete/{id}", name="app_readme_delete")
     */
    public function delete(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id): Response
    {
        // $user = $this->security->getUser();
        $repository = $doctrine->getRepository(Link::class);
        $link = $repository->find($id);

        $entityManager->remove($link);
        $entityManager->flush();

        $this->addFlash('success', new TranslatableMessage('readme.delete.success'));

        return $this->redirectToRoute('app_readme');
    }
}
