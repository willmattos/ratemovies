<?php

namespace App\Controller;

use App\Entity\Contenido;
use App\Service\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContenidoController extends AbstractController
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    #[Route('/crearCritica', name: 'crearCritica')]
    public function crearCritica(Request $request): JsonResponse
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $datos = ['contenido' => $request->request->get('codigo'), 'texto' => $request->request->get('texto')];
        $critica = $this->service->addCritica($datos, $user);
        if ($critica) {
            $this->service->addObject($critica);
            $js['respuesta'] = true;
            $js['codigo'] = $critica->getId();
            $js['fecha'] = $critica->getFecha()->format('Y/m/d H:i:s');
        }
        return new JSONResponse($js);
    }
    #[Route('/crearComentario', name: 'crearComentario')]
    public function crearComentario(Request $request): JsonResponse
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $datos = ['critica' => $request->request->get('codigo'), 'texto' => $request->request->get('texto')];
        $comentario = $this->service->addComentario($datos, $user);
        if ($comentario) {
            $js['respuesta'] = true;
            $js['codigo'] = $comentario->getId();
            $js['fecha'] = $comentario->getFecha()->format('Y/m/d H:i:s');
        }
        return new JSONResponse($js);
    }
    #[Route('/eliminarComentario', name: 'eliminarComentario')]
    public function eliminarComentario(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $datos = ['codigo' => $request->request->get('codigo'), 'tipo' => $request->request->get('tipo')];
        $object = $this->service->getCriticaOrComentarioById($datos, $user);
        if ($object) {
            $this->service->deleteObject($object);
            $js['respuesta'] = true;
        }
        return new JSONResponse($js);
    }
    #[Route('/darLike', name: 'darLike')]
    public function darLike(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $datos = ['codigo' => $request->request->get('codigo'), 'tipo' => $request->request->get('tipo')];
        $like = $this->service->getLike($datos, $user);
        $js['respuesta'] = true;
        if ($like) {
            $this->service->deleteObject($like);
            $js['tipo'] = 0;
        } else {
            $like = $this->service->addLike($datos, $user);
            if ($like) {
                $this->service->addObject($like);
                $js['tipo'] = 1;
            }
        }
        return new JSONResponse($js);
    }
    #[Route('/darFavorito', name: 'darFavorito')]
    public function darFavorito(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $id = $request->request->get('codigo');
        $contenido = $this->service->getContentById($id);
        if ($contenido) {
            $js['respuesta'] = true;
            $favorito = $this->service->getFavorito($contenido, $user);
            if ($favorito) {
                $this->service->deleteObject($favorito);
                $js['tipo'] = 0;
            } else {
                $favorito = $this->service->addFavorito($contenido, $user);
                $this->service->addObject($favorito);
                $js['tipo'] = 1;
            }
        }
        return new JSONResponse($js);
    }
    #[Route('/puntuarContenido', name: 'puntuarContenido')]
    public function puntuarContenido(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $datos = ['codigo' => $request->request->get('codigo'), 'puntuacion' => $request->request->get('puntuacion')];
        $valoracion = $this->service->getValoracion($datos, $user);
        if ($valoracion && $valoracion->getPuntuacion() == $datos['puntuacion']) {
            $this->service->deleteObject($valoracion);
            $js['respuesta'] = true;
            $js['cantidad'] = 0;
        } else if ($valoracion && $valoracion->getPuntuacion() != $datos['puntuacion']) {
            $valoracion->setPuntuacion($datos['puntuacion']);
            $this->service->updateObject();
            $js['respuesta'] = true;
            $js['cantidad'] = $datos['puntuacion'];
        } else {
            $valoracion = $this->service->addValoracion($datos, $user);
            $this->service->addObject($valoracion);
            $js['respuesta'] = true;
            $js['cantidad'] = $datos['puntuacion'];
        }
        return new JSONResponse($js);
    }
    #[Route('/crearContenido', name: 'crearContenido')]
    public function crearContenido(SessionInterface $session)
    {
        $user = $this->getUser();
        if (!$user || ($user && !$this->isGranted('ROLE_ADMIN'))) return $this->redirectToRoute('home');
    
        if (isset($_POST['tipo'], $_POST['titulo'], $_POST['alias'], $_POST['fecha'], $_POST['descripcion']) &&strlen(trim($_POST['fecha'])) &&strlen(trim($_POST['titulo'])) && strlen(trim($_POST['descripcion']))) {
            $datos = ['titulo' => ucfirst(trim($_POST['titulo'])), 'alias' => ucfirst(trim($_POST['alias'])), 'descripcion' => strlen($_POST['descripcion']) ? $_POST['descripcion'] : null, 'estreno' => strlen($_POST['fecha']) >= 10  ? new \DateTime($_POST['fecha']) : null, 'serie' => $_POST['tipo'] ? 0 : 1];
            $contenido = $this->service->addContenido($datos);
            $this->service->addObject($contenido);

            if (isset($_FILES['poster']) && strlen($_FILES['poster']['name'])) {
                $this->guardarArchivo($contenido->getId(), $_FILES['poster'], 1);
                $contenido->setPoster($_FILES['poster']['name']);
            }
            if (isset($_FILES['portada']) && strlen($_FILES['portada']['name'])) {
                $this->guardarArchivo($contenido->getId(), $_FILES['portada'], 0);
                $contenido->setPortada($_FILES['portada']['name']);
            }

            if (isset($_POST['generos'])) {
                $this->service->addGenerosToContenido($contenido, $_POST['generos']);
            }
            if (isset($_POST['reparto'])) {
                $this->service->addRepartoToContenido($contenido, $_POST['reparto']);
            }
            $this->service->updateObject();
        }

        return $this->redirectToRoute('contenido', ['id' => $contenido->getId(), 'nombre' => $contenido->getTitulo()]);
    }
    #[Route('/eliminarContenido', name: 'eliminarContenido')]
    public function eliminarContenido()
    {
        $user = $this->getUser();
        if ($user && $this->isGranted('ROLE_ADMIN') && isset($_POST['codigo'])) {
            $id = $_POST['codigo'];
            $contenido = $this->service->getContentById($id);
            if ($contenido) {
                $this->eliminarArchivo($contenido);
                $this->service->deleteObject($contenido);
            }
        }
        return $this->redirectToRoute('home');
    }
    private function eliminarArchivo(Contenido $contenido)
    {
        $parentFolderToDelete = 'public/Contenido/c' . $contenido->getId();
        $parentFolderPath = $this->getParameter('kernel.project_dir') . '/' . $parentFolderToDelete;
        if (file_exists($parentFolderPath) && is_dir($parentFolderPath)) {
            $finder = new Finder();
            $finder->files()->in($parentFolderPath)->ignoreDotFiles(false);
            foreach ($finder as $file) {
                unlink($file->getRealPath());
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($parentFolderPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $path) {
                if ($path->isDir()) {
                    rmdir($path->getPathname());
                } else {
                    unlink($path->getPathname());
                }
            }
            rmdir($parentFolderPath);
        }
    }
    private function guardarArchivo($codigo, $file, $tipo)
    {
        $filesystem = new Filesystem();
        $tipo = $tipo ? 'poster' : 'portada';
        $folderPath = $this->getParameter('kernel.project_dir') . '/public/Contenido/c' . $codigo . '/' . $tipo . '/';
        // Verificar si la carpeta existe, si no existe, crearla
        if (!$filesystem->exists($folderPath)) {
            $filesystem->mkdir($folderPath, 0777, true);
        }
        $filePath = $folderPath . $file['name'];
        move_uploaded_file($file['tmp_name'], $filePath);
    }
}
