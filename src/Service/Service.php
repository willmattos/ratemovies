<?php

namespace App\Service;

use App\Entity\Contenido;
use App\Entity\User;
use App\Repository\ContenidoRepository;
use App\Repository\GeneroContenidoRepository;
use App\Repository\GenerosRepository;
use App\Repository\LikeRepository;
use App\Repository\UserRepository;
use App\Repository\VisitaRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;

class Service
{
    private $repository_contenido;
    private $repository_visita;
    private $repository_generos;
    private $repository_genero_contenido;
    private $repository_like;
    private $repository_user;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ContenidoRepository $repository_contenido, VisitaRepository $repository_visita, GeneroContenidoRepository $repository_genero_contenido, GenerosRepository $repository_generos, LikeRepository $repository_like, UserRepository $repository_user)
    {
        $this->repository_contenido = $repository_contenido;
        $this->repository_visita = $repository_visita;
        $this->repository_generos = $repository_generos;
        $this->repository_genero_contenido = $repository_genero_contenido;
        $this->repository_like = $repository_like;
        $this->repository_user = $repository_user;
        $this->entityManager = $entityManager;
    }
    public function getMostPopular()
    {
        $mostPopularsId = $this->repository_visita->findByMostPopular();
        $popular['todo'] = null;
        $popular['pelicula'] = null;
        $popular['serie'] = null;
        foreach ($mostPopularsId as $id) {
            $content = $this->repository_contenido->findOneBy(['codigo' => $id]);
            $popular['todo'][] = $content;
            if ($content->getSerie()) {
                $popular['serie'][] = $content;
            } else {
                $popular['pelicula'][] = $content;
            }
        }
        $popular['todo'] ? shuffle($popular['todo']) : $popular['todo'] = null;
        $popular['pelicula'] ? shuffle($popular['pelicula']) : $popular['pelicula'] = null;
        $popular['serie'] ? shuffle($popular['serie']) : $popular['serie'] = null;
        return $popular;
    }
    public function getMostViews()
    {
        $mostViewsId = $this->repository_visita->findByViews();
        $content = null;
        foreach ($mostViewsId as $id) {
            $content[] = $this->repository_contenido->findOneBy(['id' => $id]);
        }
        return $content;
    }
    public function getLatestAdditions()
    {
        $novedades = [];
        $novedades['todo'] = $this->repository_contenido->findBy([], ['estreno' => 'DESC'], 16);
        $novedades['todo'] ? shuffle($novedades['todo']) : $novedades['todo'] = null;
        $novedades['peliculas'] =  $this->repository_contenido->findBy(['serie' => 0], ['estreno' => 'DESC'], 16);
        $novedades['series'] =  $this->repository_contenido->findBy(['serie' => 1], ['estreno' => 'DESC'], 16);
        $novedades['peliculas'] ? shuffle($novedades['peliculas']) : $novedades['peliculas'] = null;
        $novedades['series'] ? shuffle($novedades['series']) : $novedades['series'] = null;
        return $novedades;
    }
    public function getAllContent()
    {
        return $this->repository_contenido->findAll();
    }

    public function getGenresAndYears()
    {
        $results = $this->repository_contenido
            ->createQueryBuilder('c')
            ->select("SUBSTRING(c.estreno, 1, 4) AS year")
            ->distinct(true)
            ->orderBy('c.estreno', 'DESC')
            ->getQuery()->getResult();
        $years = [];
        foreach ($results as $result) {
            $years[] = $result['year'];
        }
        $genres = $this->repository_generos->findBy([], ['nombre' => "DESC"]);
        return ['years' => $years, 'genres' => $genres];
    }
    public function getContentById(int $id): Contenido
    {
        $content = $this->repository_contenido->findOneBy(['id' => $id]);
        return $content;
    }
    public function getContentByFilter($filter)
    {
        $filtro['titulo'] = null;
        $filtro['generos'] = [];
        $filtro['fecha'] = [];
        $filtro['tipo'] = [];
        $filtro['ordenar'] = [];

        $queryBuilder = $this->repository_genero_contenido
            ->createQueryBuilder('g')
            ->join('g.contenido', 'c')
            ->select('DISTINCT c.id');
        if (isset($filter['titulo']) && $filter['titulo']) {
            $filtro['titulo'] = $filter['titulo'];
            $titulo = trim(strtolower($filter['titulo']));
            $queryBuilder->andWhere($queryBuilder->expr()->like('LOWER(c.titulo)', ':titulo'))
                ->orWhere($queryBuilder->expr()->like('LOWER(c.alias)', ':titulo'))
                ->setParameter('titulo', '%' . $titulo . '%');
        }
        if (isset($filter['tipo'])) {
            $filtro['tipo'] = $filter['tipo'];
            $queryBuilder->andwhere($queryBuilder->expr()->in('c.serie', $filter['tipo']));
        }
        if (isset($filter['generos']) && $filter['generos']) {
            $filtro['generos'] = $filter['generos'];
            $queryBuilder->andwhere($queryBuilder->expr()->in('g.cod_genero', $filter['generos']));
        }
        if (isset($filter['fecha']) && $filter['fecha']) {
            $filtro['fecha'] = $filter['fecha'];
            $queryBuilder->andwhere($queryBuilder->expr()->in('SUBSTRING(c.estreno, 1, 4)', $filter['fecha']));
        }
        if (isset($filter['ordenar'])) {
            $filtro['ordenar'][] = $filter['ordenar'];
            $order = $filter['ordenar'] ? 'ASC' : 'DESC';
            $queryBuilder->orderBy('c.estreno', $order);
        }

        $results = $queryBuilder->getQuery()->getResult();
        $contenido = $this->repository_contenido->findBy(['id' => $results]);

        return ['contenido' => $contenido, 'filtro' => $filtro];
    }
    public function getRecommendedByGenre(int $id, Collection $genres)
    {
        $genreIds = [];
        foreach ($genres as $genre) {
            $genreIds[] = $genre->getGenero()->getId();
        }

        $queryBuilder = $this->repository_contenido->createQueryBuilder('c');

        // Construye la consulta
        $result = $queryBuilder
            ->select('c.id')
            ->leftJoin('c.generos', 'g')
            ->leftJoin('g.genero', 'gr')
            ->where($queryBuilder->expr()->in('gr.id', $genreIds))
            ->andWhere('c.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        $content = $this->repository_contenido->findBy(['id' => $result]);

        return $content;
    }
    public function hasMyOwnLike(Collection $criticas, User $user)
    {
        foreach ($criticas as $critica) {
            $result = $this->repository_like->findOneBy(['critica' => $critica, 'user' => $user]) ? true : false;
            $critica->setMyLike($result);

            $comentarios = $critica->getComentarios();
            foreach ($comentarios as $comentario) {
                $result = $this->repository_like->findOneBy(['comentario' => $comentario, 'user' => $user]) ? true : false;
                $comentario->setMyLike($result);
            }
        }
        return $criticas;
    }
    public function getUserByEMailOrUsername($email, $username)
    {
        $user = $this->repository_user->createQueryBuilder('u')
            ->andWhere('u.email = :email OR LOWER(u.username) = LOWER(:username)')
            ->setParameter('email', $email)
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
        return $user;
    }
    public function addUser($email, $username): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setRol(0);
        $user->setActivado(0);
        $user->setBloquear(0);
        do {
            $recuperacion = rand(1, 2147483647);
        } while ($this->repository_user->findOneBy(['recuperar' => $recuperacion]));
        $user->setRecuperar($recuperacion);
        return $user;
    }
    public function createEmail($SERVER, $user): Email
    {
        $recuperacion = $user->getRecuperar();
        $user_email = $user->getEmail();

        $url = $this->url_origin($SERVER);
        $url = $url . "/activarCuenta/$recuperacion";

        $email = (new Email())->from('willmattos.services@gmail.com')
            ->to($user_email)
            ->subject('Activac cuenta')
            ->html("<a href=\"$url\">Activa tu cuenta</a>");

        return $email;
    }
    public function addObject($object){
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    private function url_origin($s, $use_forwarded_host = false): string
    {

        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');

        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;

        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;

        return $protocol . '://' . $host;
    }
}
