<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'app_comment')]
    public function create(Animal $animal, EntityManagerInterface $manager, Request $request): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAnimal($animal);
            $manager->persist($comment);
            $manager->flush();
        }
        return $this->redirectToRoute('showAnimalComments', ['animal' => $animal->getId()]);

    }
}
