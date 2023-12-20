<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, ActorRepository $repo_actor): Response
    {
        // $actor = new Actor();
        // $actor->setNombre("Rosalia");
        // $entityManager->persist($actor);
        // $entityManager->flush();
        $all = $repo_actor->findAll();
        return new JsonResponse($all);
        // return $this->render('user/index.html.twig', [
        //     'controller_name' => 'UserController',
        // ]);
    }
}
