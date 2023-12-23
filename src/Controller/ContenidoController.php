<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContenidoController extends AbstractController
{
    #[Route('/contenido', name: 'app_contenido')]
    public function index(): Response
    {
        return $this->render('contenido/index.html.twig', [
            'controller_name' => 'ContenidoController',
        ]);
    }
    #[Route('/crearCritica', name: 'crearCritica')]
    public function crearCritica(Request $request)
    {}
    #[Route('/crearComentario', name: 'crearComentario')]
    public function crearComentario(Request $request)
    {}
    #[Route('/eliminarComentario', name: 'eliminarComentario')]
    public function eliminarComentario(Request $request)
    {}
    #[Route('/darLike', name: 'darLike')]
    public function darLike(Request $request)
    {}
    #[Route('/darFavorito', name: 'darFavorito')]
    public function darFavorito(Request $request)
    {}
    #[Route('/puntuarContenido', name: 'puntuarContenido')]
    public function puntuarContenido(Request $request)
    {}
    #[Route('/crearContenido', name: 'crearContenido')]
    public function crearContenido(SessionInterface $session)
    {}
    #[Route('/eliminarContenido', name: 'eliminarContenido')]
    public function eliminarContenido()
    {}
    #[Route('/agregarVisita', name: 'agregarVisita')]
    public function agregarVisita(Request $request)
    {}
}
