<?php

namespace App\Controller;

use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use phpDocumentor\Reflection\Types\Integer;

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

        /**
         * Will del futuro, modifica o haz una restriccion cuando se añada una pelicula que tenga un genero obligatorio ya que en servicio aplicamos la busqueda desde genre
         * creo que más facil será que crear con un genero obligatorio
         * 
         * NO OLVIDAR
         */

        $result = $this->service->getGenresAndYears();
        $genres = $result['genres'];
        $years =  $result['years'];
        $contents = [];
        $filtros = ['titulo' => null, 'generos' => [], 'fecha' => [], 'tipo' => [], 'ordenar' => []];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->service->getContentByFilter($_POST);
            $filtros = $result['filtro'];
            $contents = $result['contenido'];
        } else {
            $contents = $this->service->getAllContent();
        }
        return $this->render('catalogo.html.twig', ['generos' => $genres, 'fecha' => $years, 'contenidos' => $contents, 'filtros' => json_encode($filtros), 'titulo_buscar' => $filtros['titulo']]);
    }
    #[Route('/comunidad', name: 'comunidad')]
    public function comunidad()
    {
        $filePath = 'sql/ratemovies.sql';
        $message = "";
        if (file_exists($filePath)) {
            $sqlContent = file_get_contents($filePath);

            try {
                $this->connection->exec($sqlContent);
                $message = 'Archivo SQL importado correctamente.';
            } catch (\Exception $e) {
                $message = 'Error al importar el archivo SQL: ' . $e->getMessage();
            }
        } else {
            $message = 'El archivo SQL no existe.';
        }
        var_dump($message);die;
    }
    #[Route('/catalogo/{id}/{nombre}', name: 'contenido')]
    public function contenido(int $id)
    {
        $contenido = $this->service->getContentById($id);
        if (!$contenido) {
            return $this->redirectToRoute('home');
        }
        
        $recomendados = $this->service->getRecommendedByGenre($id, $contenido->getGeneros());
        $criticas = $contenido->getCriticas();
        $user = $this->getUser();

        if($user)$criticas = $this->service->hasMyOwnLike($criticas, $user);

        $valoraciones = $contenido->getValoraciones();
        $myScore = 0;
        $puntuacion = 0;
        for ($i = 0; $i < count($valoraciones); $i++) {
            $puntuacion += $valoraciones[$i]->getPuntuacion();
            $usuario = $valoraciones[$i]->getUser();
            if ($usuario == $user) {
                $myScore = $valoraciones[$i]->getPuntuacion();
            }
        }
        $puntuacion = count($valoraciones) ? ($puntuacion * 100) / (5 * count($valoraciones)) : 0;

        return $this->render('contenido.html.twig', ['contenido' => $contenido, 'recomendados' => $recomendados, 'criticas' => $criticas, 'valora' => $myScore, 'puntuacion' => $puntuacion]);
    }

    #[Route('/perfil', name: 'perfil')]
    public function perfil(SessionInterface $session)
    {
        if (!$this->getUser()) {
            $errorMessages = $session->getFlashBag()->get('error');
            return $this->render('login.html.twig', array("errorMessages" => $errorMessages));
        }
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
