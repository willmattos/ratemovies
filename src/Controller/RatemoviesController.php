<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Service;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RatemoviesController extends AbstractController
{
    private $service;
    private $connection;
    private $urlGenerator;

    public function __construct(Service $service, Connection $connection, UrlGeneratorInterface $urlGenerator)
    {
        $this->service = $service;
        $this->connection = $connection;
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $destacados = $this->service->getMostViews();
        $popular = $this->service->getMostPopular();
        $latest = $this->service->getLatestAdditions();
        return $this->render('home.html.twig', ['destacados' => $destacados, 'popular' => $popular, 'novedades' => $latest['todo'], 'novedades_peliculas' => $latest['peliculas'], 'novedades_series' => $latest['series']]);
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
            shuffle($contents);
        }
        return $this->render('catalogo.html.twig', ['generos' => $genres, 'fecha' => $years, 'contenidos' => $contents, 'filtros' => json_encode($filtros), 'titulo_buscar' => $filtros['titulo']]);
    }
    #[Route('/comunidad', name: 'comunidad')]
    public function comunidad()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('perfil');
        }
        $siguiendo = $this->getUser()->getSiguiendo();
        $users = [$user];
        foreach ($siguiendo as $usuario) {
            $users[] = $usuario->getFollowing();
        }
        $criticas = $this->service->getCriticasDeUsuarios($users);
        $criticas = new ArrayCollection($criticas);
        $criticas = $this->service->hasMyOwnLike($criticas, $user);

        return $this->render('comunidad.html.twig', ['criticas' => $criticas]);
    }
    #[Route('/catalogo/{id}/{nombre}', name: 'contenido')]
    public function contenido(int $id)
    {
        $contenido = $this->service->getContentById($id);
        if (!$contenido) {
            return $this->redirectToRoute('home');
        }

        $recomendados = $this->service->getRecommendedByGenre($contenido);
        $criticas = $contenido->getCriticas();
        $user = $this->getUser();

        if ($user) {
            $criticas = $this->service->getCriticasOrderByFecha($contenido);
            $criticas = new ArrayCollection($criticas);
            $criticas = $this->service->hasMyOwnLike($criticas, $user);
            $this->service->addVisita($contenido, $user);
            $esFavorito = $this->service->isContenidoFavorito($contenido, $user);
            $contenido->setOwnlike($esFavorito);
        }

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
        $criticas = $this->getUser()->getCriticas();
        $user = $this->getUser();
        $criticas = $this->service->getCriticasByUserOrderByFecha($user);
        $criticas = new ArrayCollection($criticas);
        $criticas = $this->service->hasMyOwnLike($criticas, $user);
        $following = $user->getSiguiendo();
        $followers = $user->getSeguidores();
        $favoritos = $user->getFavoritos();
        return $this->render('perfil.html.twig', ['usuario' => $this->getUser(), 'followings' => $following, 'followers' => $followers, 'favoritos' => $favoritos, 'criticas' => $criticas]);
    }
    #[Route('/perfil/{username}', name: 'usuario')]
    public function usuario($username)
    {
        $myUser = $this->getUser();
        $user = $this->service->getUserByUsername($username);
        if (!$user || ($user && $myUser && $user->getId() == $myUser->getId())) {
            return $this->redirectToRoute('perfil');
        }
        $criticas = $user->getCriticas();
        $es_seguido = null;
        if ($myUser) {
            $criticas = $this->service->hasMyOwnLike($criticas, $myUser);
            $es_seguido = $this->service->hasMyFollow($user, $myUser);
        }
        $following = $user->getSiguiendo();
        $followers = $user->getSeguidores();
        $favoritos = $user->getFavoritos();
        return $this->render('perfil.html.twig', ['usuario' => $user, 'followings' => $following, 'followers' => $followers, 'favoritos' => $favoritos, 'criticas' => $criticas, 'es_seguido' => $es_seguido]);
    }
    #[Route('/nuevo_contenido', name: 'nuevo_contenido')]
    public function admin()
    {
        $user = $this->getUser();
        if ($user && $this->isGranted('ROLE_ADMIN')) {
            $generos = $this->service->getAllGeneros();
            $actores = $this->service->getAllActor();
            return $this->render('nuevo_contenido.html.twig', ["generos" => $generos, "error" => false, 'actores' => $actores]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
    #[Route('/administracion', name: 'administracion')]
    public function administracion()
    {
        $user = $this->getUser();
        if ($user && $this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('administrador.html.twig');
        } else {
            return $this->redirectToRoute('home');
        }
    }
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        return $this->redirectToRoute('perfil');
    }
    // #[Route('/agregarDatos', name: 'agregarDatos')]
    // public function agregarDatos()
    // {
    //     $filePath = 'sql/ratemovies.sql';
    //     if (file_exists($filePath)) {
    //         $sqlContent = file_get_contents($filePath);
    //         $this->connection->exec($sqlContent);
    //     }
    //     return $this->redirectToRoute('home');
    // }
}
