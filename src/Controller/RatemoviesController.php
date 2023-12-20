<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
class RatemoviesController extends AbstractController
{
    private $service;
    private $connection;

    public function __construct(Service $service, Connection $connection)
    {
        $this->service = $service;
        $this->connection = $connection;
    }

    #[Route('/ratemovies', name: 'home')]
    public function home(): Response
    {
        $mostViews = $this->service->getMostViews();
        $destacados = $this->service->getMostPopular();
        $latest = $this->service->getLatestAdditions();
        
        return $this->render('home.html.twig', ['destacados' => $mostViews, 'popular' => $destacados, 'novedades' => $latest['todo'], 'novedades_peliculas' => $latest['peliculas'], 'novedades_series' => $latest['series']]);
    }
    #[Route('/catalogo', name: 'catalogo')]
    public function catalogo()
    {
       $contents = [];
       $filtros['titulo'] = null;
       $filtros['generos'] = [];
       $filtros['fecha'] = [];
       $filtros['tipo'] = [];
       $filtros['ordenar'] = [];
        // $filePath = 'ratemovies.sql';

        // if (file_exists($filePath)) {
        //     $sqlContent = file_get_contents($filePath);
        
        //     try {
        //         $this->connection->exec($sqlContent);
        //         $message = 'Archivo SQL importado correctamente.';
        //     } catch (\Exception $e) {
        //         $message = 'Error al importar el archivo SQL: ' . $e->getMessage();
        //     }
        // } else {
        //     $message = 'El archivo SQL no existe.';
        // }
        $contents = $this->service->getAllContent();
        return $this->render('catalogo.html.twig', ['generos' => [], 'fecha' => [], 'contenidos' => $contents, 'filtros' => json_encode($filtros), 'titulo_buscar' => $filtros['titulo']]);
    }
    #[Route('/comunidad', name: 'comunidad')]
    public function comunidad()
    {
    }
    #[Route('/catalogo/{codigo}/{nombre}', name: 'contenido')]
    public function contenido($codigo = 0, $nombre = 0)
    {
    }
    #[Route('/perfil', name: 'perfil')]
    public function perfil(SessionInterface $session)
    {
    }
    #[Route('/perfil/{username}', name: 'usuario')]
    public function usuario($username = 0)
    {
    }
    #[Route('/nuevo_contenido', name: 'nuevo_contenido')]
    public function admin()
    {
    }
    #[Route('/administracion', name: 'administracion')]
    public function administracion()
    {
    }
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        return $this->redirectToRoute('perfil');
    }
}
