<?php
// src/Controller/AdminArticleController.php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/articles')]
class AdminArticleController extends AbstractController
{
    #[Route('/', name: 'admin_article_index')]
    public function index(ArticleRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin_article/index.html.twig', [
            'articles' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_article_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $filename = $slugger->slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('articles_directory'), $filename);
                $article->setImagePath($filename);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article publié.');
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin_article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_article_delete')]
    public function delete(Article $article, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Article supprimé.');
        return $this->redirectToRoute('admin_article_index');
    }
}
