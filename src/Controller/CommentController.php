<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{

    #[Route('/comment{id}', name: 'comment')]
    public function create(Animal $animal, EntityManagerInterface $manager, Request $request): Response
    {
        $comment = new Comment();
        $comment->setAnimal($animal);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
        }
        return $this->redirectToRoute("show_animal", ["id" => $animal->getId()]);

    }
    #[Route('/comment/delete/{id}', name:'delete_comment')]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('show_animal', ["id" => $comment->getAnimal()->getId()]);
    }
}
