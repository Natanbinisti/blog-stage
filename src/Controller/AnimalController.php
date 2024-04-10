<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Comment;
use App\Entity\Image;
use App\Form\AnimalType;
use App\Form\CommentType;
use App\Form\ImageType;
use App\Repository\AnimalRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_animal')]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('animal/index.html.twig', [
            'animal'=>$animalRepository->findAll(),
        ]);
    }
    #[Route('/animal/create', name: 'create_animal')]
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $animal = New Animal();
        $formAnimal = $this->createForm(AnimalType::class,$animal);
        $formAnimal->handleRequest($request);

        if ($formAnimal->isSubmitted() && $formAnimal->isValid())
        {
            $animal->setCreatedAt(new \DateTime());
            $animal->setAuthor($this->getUser());
            $manager->persist($animal);
            $manager->flush();
            return $this->redirectToRoute('app_animal');
        }
        return $this->render('animal/create.html.twig', [
            'formAnimal'=>$formAnimal->createView()
        ]);
    }
    #[Route('animal/{id}', name:'show_animal')]
    public function show(Animal $animal):Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('animal/show.html.twig',[
            'animal'=>$animal,
            'commentForm'=>$commentForm
        ]);
    }
    #[Route('/animal/delete/{id}', name:'delete_animal')]
    public function delete(Animal $animal, Request $request, EntityManagerInterface $manager): Response
    {
        $manager->remove($animal);
        $manager->flush();
        return $this->redirectToRoute('app_animal');
    }
    #[Route('/animal/edit/{id}', name:'edit_animal')]
    public function edit(Animal $animal, Request $request, EntityManagerInterface $manager): Response
    {
        $formAnimal = $this->createForm(AnimalType::class, $animal);
        $formAnimal->handleRequest($request);
        if ($formAnimal->isSubmitted() && $formAnimal->isValid()){
            $manager->persist($animal);
            $manager->flush();
            return $this->redirectToRoute('app_animal');
        }
        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'formAnimal' => $formAnimal->createView()
        ]);
    }

    #[Route('/image/index', name:'animal_image')]
    public function addImage(Animal $animal):Response
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);

        return $this->render('image/index.html.twig', [
            "animal"=>$animal,
            "formImage"=>$formImage->createView()
        ]);
    }
}
