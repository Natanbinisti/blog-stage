<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use Doctrine\ORM\Mapping\Entity;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal', name: 'app_animal')]
    public function index(): Response
    {
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }
    #[Route('/animal/create', name: 'create')]
    public function create(): Response
    {
        $animal = New Animal();
        $formAnimal = $this->createForm(AnimalType::class,$animal);

        return $this->render('animal/create.html.twig', [
            'controller_name' => 'AnimalController',
            'formAnimal'=>$formAnimal
        ]);
    }
}
