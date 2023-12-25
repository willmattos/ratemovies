<?php

namespace App\Service;

use App\Entity\Actor;
use App\Entity\Comentario;
use App\Entity\Contenido;
use App\Entity\Critica;
use App\Entity\Favorito;
use App\Entity\GeneroContenido;
use App\Entity\Generos;
use App\Entity\Like;
use App\Entity\Reparto;
use App\Entity\Siguiendo;
use App\Entity\User;
use App\Entity\Valora;
use App\Entity\Visita;
use App\Repository\ActorRepository;
use App\Repository\ComentarioRepository;
use App\Repository\ContenidoRepository;
use App\Repository\CriticaRepository;
use App\Repository\FavoritoRepository;
use App\Repository\GeneroContenidoRepository;
use App\Repository\GenerosRepository;
use App\Repository\LikeRepository;
use App\Repository\RepartoRepository;
use App\Repository\SiguiendoRepository;
use App\Repository\UserRepository;
use App\Repository\ValoraRepository;
use App\Repository\VisitaRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;
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
	private $repository_critica;
	private $repository_siguiendo;
	private $repository_actor;
	private $repository_comentario;
	private $repository_favorito;
	private $repository_valoracion;
	private $repository_reparto;

	public function __construct(EntityManagerInterface $entityManager, ContenidoRepository $repository_contenido, VisitaRepository $repository_visita, GeneroContenidoRepository $repository_genero_contenido, GenerosRepository $repository_generos, LikeRepository $repository_like, UserRepository $repository_user, CriticaRepository $repository_critica, SiguiendoRepository $repository_siguiendo, ActorRepository $repository_actor, ComentarioRepository $repository_comentario, FavoritoRepository $repository_favorito, ValoraRepository $repository_valoracion, RepartoRepository $repository_reparto)
	{
		$this->repository_contenido = $repository_contenido;
		$this->repository_visita = $repository_visita;
		$this->repository_generos = $repository_generos;
		$this->repository_genero_contenido = $repository_genero_contenido;
		$this->repository_like = $repository_like;
		$this->repository_user = $repository_user;
		$this->repository_critica = $repository_critica;
		$this->entityManager = $entityManager;
		$this->repository_actor = $repository_actor;
		$this->repository_siguiendo = $repository_siguiendo;
		$this->repository_comentario = $repository_comentario;
		$this->repository_favorito = $repository_favorito;
		$this->repository_valoracion = $repository_valoracion;
		$this->repository_reparto = $repository_reparto;
	}
	public function getMostPopular()
	{
		$mostPopularsId = $this->repository_visita->findByMostPopular();

		$ids = [];
		foreach ($mostPopularsId as $id) {
			$ids[] = $id['id'];
		}
		$ids = array_unique($ids);
		$contents = $this->repository_contenido->findBy(['id' => $ids], [], 16);
		$popular['todo'] = $contents;
		$popular['pelicula'] = null;
		$popular['serie'] = null;
		foreach ($contents as $content) {
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
		$ids = [];
		foreach ($mostViewsId as $id) {
			$ids[] = $id['id'];
		}
		$content = $this->repository_contenido->findBy(['id' => $ids]);
		shuffle($content);
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
			->select("DISTINCT(SUBSTRING(c.estreno, 1, 4)) AS year")
			->orderBy('year', 'DESC')
			->getQuery()
			->getResult();
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
		$queryBuilder = $this->repository_contenido->createQueryBuilder('c')
			->join('c.generos', 'g')
			->join('g.genero', 'gr')
			->select('DISTINCT(c.id)');
		if (isset($filter['titulo']) && $filter['titulo']) {
			$filtro['titulo'] = $filter['titulo'];
			$titulo = trim(strtolower($filter['titulo']));
			$queryBuilder->andWhere("LOWER(c.titulo) LIKE '%" . $titulo . "%'")
				->orWhere("LOWER(c.alias) LIKE '%" . $titulo . "%'");
		}
		if (isset($filter['tipo'])) {
			$filtro['tipo'] = $filter['tipo'];
			$queryBuilder->andwhere($queryBuilder->expr()->in('c.serie', $filter['tipo']));
		}

		if (isset($filter['generos']) && $filter['generos']) {
			$filtro['generos'] = $filter['generos'];
			$queryBuilder->andwhere($queryBuilder->expr()->in('gr.id', $filter['generos']));
		}
		if (isset($filter['fecha']) && $filter['fecha']) {
			$filtro['fecha'] = $filter['fecha'];
			$queryBuilder->andwhere($queryBuilder->expr()->in('SUBSTRING(c.estreno, 1, 4)', $filter['fecha']));
		}
		$order = [];
		if (isset($filter['ordenar'])) {
			$filtro['ordenar'][] = $filter['ordenar'];
			$order = $filter['ordenar'] ? 'ASC' : 'DESC';
			// $queryBuilder->orderBy('c.estreno', $order);
			$order = ['estreno' => $order];
		}

		$results = $queryBuilder->getQuery()->getResult();
		$ids = [];
		foreach ($results as $key) {
			$ids[] = $key[1];
		}
		$contenido = $this->repository_contenido->findBy(['id' => $ids], $order);
		return ['contenido' => $contenido, 'filtro' =>  $filtro];
	}
	public function getRecommendedByGenre(Contenido $contenido)
	{
		$genreIds = [];
		$id = $contenido->getId();
		$generos = $contenido->getGeneros();
		foreach ($generos as $genre) {
			$genreIds[] = $genre->getGenero()->getId();
		}

		$queryBuilder = $this->repository_contenido
			->createQueryBuilder('c')
			->select('DISTINCT(c.id)')
			->join('c.generos', 'g')
			->join('g.genero', 'gr');
		$results = $queryBuilder
			->where($queryBuilder->expr()->in('gr.id', $genreIds))
			->andWhere('c.id != :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult();
		$contentIds = [];
		foreach ($results as $result) {
			$contentIds[] = $result[1];
		}
		$content = $this->repository_contenido->findBy(['id' => $contentIds], [], 8);

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

	public function hasMyFollow($user, $myUser)
	{
		$siguiendo = $this->repository_siguiendo->findOneBy(['follower' => $myUser, 'following' => $user]);
		return $siguiendo;
	}

	public function getUserByEmail($email)
	{
		$user = $this->repository_user->findOneBy(['email' => $email]);
		return $user;
	}
	public function getAllGeneros()
	{
		$generos = $this->repository_generos->findBy([], ['nombre' => 'ASC']);
		return $generos;
	}
	public function getAllActor()
	{
		$actores = $this->repository_actor->findBy([], ['nombre' => 'ASC']);
		return $actores;
	}
	public function getUserByUsername($username)
	{
		$user = $this->repository_user->createQueryBuilder('u')
			->andWhere('LOWER(u.username) = LOWER(:username)')
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
		$caducidad = new \DateTime();
		$caducidad = $caducidad->add(new \DateInterval('P1D'));
		$user->setCaducidad($caducidad);
		do {
			$recuperacion = rand(1, 2147483647);
		} while ($this->repository_user->findOneBy(['recuperar' => $recuperacion]));
		$user->setRecuperar($recuperacion);
		return $user;
	}

	public function getUserByRecuperar($recuperacion)
	{
		$user = $this->repository_user->findOneBy(['recuperar' => $recuperacion]);
		return $user;
	}
	public function getUserById($id)
	{
		$user = $this->repository_user->findOneBy(['id' => $id]);
		return $user;
	}

	public function emailCreateUser($SERVER, $user): Email
	{
		$recuperacion = $user->getRecuperar();
		$user_email = $user->getEmail();

		$url = $this->url_origin($SERVER);
		$url = $url . "/activarCuenta/$recuperacion";

		$email = (new Email())
			->from(new Address('willmattos.services@gmail.com', 'Ratemovies'))
			->to($user_email)
			->subject('Bienvenido a Ratemovies')
			->html("<!DOCTYPE html>
			<html lang='en'>
			<head>
			  <meta charset='UTF-8'>
			  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
			  <title>Activación de Usuario</title>
			</head>
			<body>
			  <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>
			  <h2>Bienvenido a Ratemovies</h2>
				<p>Gracias por registrarte en Ratemovies. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
				<p><a href=\"$url\" style='display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px;'>Activar Usuario</a></p>
				<p>Si no puedes hacer clic en el enlace, cópialo y pégalo en la barra de direcciones de tu navegador:</p>
				<p>$url</p>
				<p>Este enlace es válido por 24 horas.</p>
				<p>Gracias,<br>willmattos.c@gmail.com</p>
			  </div>
			</body>
			</html>");


		return $email;
	}
	public function emailRecuperateUser(User $user, $SERVER): Email
	{
		$user_email = $user->getEmail();

		do {
			$recuperacion = rand(1, 2147483647);
		} while ($this->repository_user->findOneBy(['recuperar' => $recuperacion]));

		$user->setRecuperar($recuperacion);
		$caducidad = new \DateTime();
		$caducidad = $caducidad->add(new \DateInterval('P1D'));
		$user->setCaducidad($caducidad);

		$url = $this->url_origin($SERVER);
		$url = $url . "/recuperarCuenta/$recuperacion";

		$email = (new Email())
			->from(new Address('willmattos.services@gmail.com', 'Ratemovies'))
			->to($user_email)
			->subject('Recuperación de Cuenta - Ratemovies')
			->html("<!DOCTYPE html>
			<html lang='en'>
			<head>
			  <meta charset='UTF-8'>
			  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
			  <title>Recuperación de Cuenta - Ratemovies</title>
			</head>
			<body>
			  <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>
				<h2>Recuperación de Cuenta - Ratemovies</h2>
				<p>Hemos recibido una solicitud para recuperar tu cuenta en Ratemovies. Si no has realizado esta solicitud, puedes ignorar este mensaje.</p>
				<p>Para restablecer tu cuenta, haz clic en el siguiente enlace:</p>
				<p><a href=\"$url\" style='display: inline-block; padding: 10px 20px; background-color: #007BFF; color: #fff; text-decoration: none; border-radius: 5px;'>Restablecer Cuenta</a></p>
				<p>Si no puedes hacer clic en el enlace, cópialo y pégalo en la barra de direcciones de tu navegador:</p>
				<p>$url</p>
				<p>Este enlace es válido por 24 horas.</p>
				<p>Gracias,<br>willmattos.c@gmail.com</p>
			  </div>
			</body>
			</html>
			");

		return $email;
	}

	public function getCriticasDeUsuarios($users)
	{
		$criticas = $this->repository_critica->findBy(['user' => $users], ['fecha' => 'DESC']);
		// $queryBuilder = $this->repository_critica->createQueryBuilder('c');
		// $criticas =  $queryBuilder->where($queryBuilder->expr()->in('c.user', $users))
		// 	->orderBy('c.fecha', 'DESC')
		// 	->getQuery()
		// 	->getResult();
		return $criticas;
	}

	public function addObject($object): void
	{
		$this->entityManager->persist($object);
		$this->entityManager->flush();
	}
	public function updateObject(): void
	{
		$this->entityManager->flush();
	}
	public function deleteObject($object)
	{
		$this->entityManager->remove($object);
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
	public function addVisita(Contenido $contenido, User $user): void
	{
		$visita = $this->repository_visita->findOneBy(['contenido' => $contenido, 'fecha' => new \DateTime(), 'user' => $user]);
		if (!$visita) {
			$visita = new Visita();
			$visita->setContenido($contenido);
			$visita->setFecha(new \DateTime());
			$visita->setUser($user);
			$this->addObject($visita);
		}
	}
	public function addCritica($datos, $user)
	{
		$critica = null;
		$contenido = $this->repository_contenido->findOneBy(['id' => $datos['contenido']]);
		if ($contenido) {
			$critica = new Critica();
			$critica->setContenido($contenido);
			$critica->setComentario($datos['texto']);
			$critica->setUser($user);
			$critica->setFecha(new \DateTime());
		}
		return $critica;
	}
	public function addComentario($datos, $user)
	{
		$comentario = null;
		$critica = $this->repository_critica->findOneBy(['id' => $datos['critica']]);
		if ($critica) {
			$comentario = new Comentario();
			$comentario->setComentario($datos['texto']);
			$comentario->setUser($user);
			$comentario->setFecha(new \DateTime());
			$critica->addComentario($comentario);
			$this->addObject($comentario);
		}
		return $comentario;
	}
	public function getCriticaOrComentarioById($datos, $user)
	{
		$id = $datos['codigo'];
		$object = null;
		if ($datos['tipo']) {
			$object = $this->repository_critica->findOneBy(['id' => $id, 'user' => $user]);
		} else {
			$object = $this->repository_comentario->findOneBy(['id' => $id, 'user' => $user]);
		}
		return $object;
	}

	public function getLike($datos, $user)
	{
		$id = $datos['codigo'];
		$object = null;
		if ($datos['tipo']) {
			$datos['tipo'] = "critica";
			$object = $this->getCritica($id);
		} else {
			$datos['tipo'] = "comentario";
			$object = $this->getComentario($id);
		}
		$like = $this->repository_like->findOneBy([$datos['tipo'] => $object, 'user' => $user]);
		return $like;
	}
	public function addLike($datos, $user)
	{
		$id = $datos['codigo'];
		$object = null;
		if ($datos['tipo']) {
			$object = $this->getCritica($id);
		} else {
			$object = $this->getComentario($id);
		}
		if (!$object) return $object;

		$like = new Like();
		$like->setUser($user);
		if ($datos['tipo']) $like->setCritica($object);
		else $like->setComentario($object);
		return $like;
	}
	public function getCritica($id)
	{
		$critica = $this->repository_critica->findOneBy(['id' => $id]);
		return $critica;
	}
	public function getComentario($id)
	{
		$critica = $this->repository_comentario->findOneBy(['id' => $id]);
		return $critica;
	}
	public function getFavorito($contenido, $user)
	{
		$favorito = $this->repository_favorito->findOneBy(['contenido' => $contenido, 'user' => $user]);
		return $favorito;
	}
	public function addFavorito($contenido, $user): Favorito
	{
		$favorito = new Favorito();
		$favorito->setContenido($contenido);
		$favorito->setUser($user);
		return $favorito;
	}
	public function getValoracion($datos, $user)
	{
		$valoracion = null;
		$id = $datos['codigo'];
		$contenido = $this->getContentById($id);

		if ($contenido) {
			$valoracion = $this->repository_valoracion->findOneBy(['contenido' => $contenido, 'user' => $user]);
		}
		return $valoracion;
	}
	public function addValoracion($datos, $user)
	{
		$valoracion = null;
		$id = $datos['codigo'];
		$puntuacion = $datos['puntuacion'];
		$contenido = $this->getContentById($id);

		if ($contenido) {
			$valoracion = new Valora();
			$valoracion->setUser($user);
			$valoracion->setContenido($contenido);
			$valoracion->setPuntuacion($puntuacion);
		}
		return $valoracion;
	}
	public function addContenido($datos)
	{
		$contenido = new Contenido();
		$contenido->setTitulo($datos['titulo']);
		$contenido->setAlias($datos['alias']);
		$contenido->setDescripcion($datos['descripcion']);
		$contenido->setEstreno($datos['estreno']);
		$contenido->setSerie($datos['serie']);
		return $contenido;
	}
	public function addGenerosToContenido(Contenido $contenido, $generos): void
	{
		foreach ($generos as $genero) {
			$nombre = ucfirst(trim($genero));
			$genero = $this->repository_generos->findOneBy(['nombre' => $nombre]);
			if (!$genero) {
				$genero = new Generos();
				$genero->setNombre($nombre);
				$this->addObject($genero);
			}
			$genero_contenido = new GeneroContenido();
			$genero_contenido->setContenido($contenido);
			$genero_contenido->setGenero($genero);
			$this->addObject($genero_contenido);
		}
	}
	public function addRepartoToContenido(Contenido $contenido, $actores): void
	{
		foreach ($actores as $actor) {
			$nombre = ucfirst(trim($actor));
			$actor = $this->repository_actor->findOneBy(['nombre' => $nombre]);
			if (!$actor) {
				$actor = new Actor();
				$actor->setNombre($nombre);
				$this->addObject($actor);
			}
			$reparto = new Reparto();
			$reparto->setContenido($contenido);
			$reparto->setActor($actor);
			$this->addObject($reparto);
		}
	}
	public function getSiguiendo($user, $myUser)
	{
		$siguiendo = $this->repository_siguiendo->findOneBy(['following' => $user, 'follower' => $myUser]);
		return $siguiendo;
	}
	public function addSiguiendo($user, $myUser)
	{
		$siguiendo = new Siguiendo();
		$siguiendo->setFollower($myUser);
		$siguiendo->setFollowing($user);
		return $siguiendo;
	}
	public function getAllUser($myId)
	{
		$users = $this->repository_user->createQueryBuilder('u')
			->where("u.id != :id")
			->setParameter('id', $myId)
			->getQuery()
			->getResult();
		return $users;
		return $users;
	}
	public function getUsersLikeUsername($username, $myId)
	{
		$username = strtolower($username);
		$users = $this->repository_user->createQueryBuilder('u')
			->where("LOWER(u.username) LIKE '%" . $username . "%'")
			->andWhere('u.id != :id')
			->setParameter('id', $myId)
			->getQuery()
			->getResult();
		return $users;
	}
	public function isContenidoFavorito(Contenido $contenido, User $user)
	{
		$favorito = $this->repository_favorito->findOneBy(['contenido' => $contenido, 'user' => $user]);
		return $favorito;
	}
	public function getCriticasOrderByFecha(Contenido $contenido)
	{
		$content = $this->repository_critica->findBy(['contenido' => $contenido], ['fecha' => 'DESC']);
		return $content;
	}
	public function getComentariosOrderByFecha(Critica $critica)
	{
		$comentarios = $this->repository_comentario->findBy(['critica' => $critica], ['fecha' => 'DESC']);
		return $comentarios;
	}
	public function getCriticasByUserOrderByFecha(User $user)
	{
		$content = $this->repository_critica->findBy(['user' => $user], ['fecha' => 'DESC']);
		return $content;
	}
}
