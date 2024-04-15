<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Animal;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/image/add/animal/{id}', name: 'add_animal_image')]
    public function index($id, Request $request, EntityManagerInterface $manager): Response
    {

        //determiner la route utilisée
        $route = $request->attributes->get("_route");

        //en fonction de la route, récuperer la bonne entité

        switch ($route){

            case 'add_animal_image':
                $entity = Animal::class;
                $setter = "setAnimal";
                $redirectRoute = "animal_image";
                $routeParam= ["id"=>$id];
                break;


        }


        $toBeAddedAnImage = $manager->getRepository($entity)->find($id);



        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid())
        {

            $image->$setter($toBeAddedAnImage);
            $manager->persist($image);
            $manager->flush();

        }



        return $this->redirectToRoute($redirectRoute, $routeParam);
    }


}