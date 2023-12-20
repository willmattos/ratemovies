<?php

namespace App\Service;

use App\Repository\ContenidoRepository;
use App\Repository\VisitaRepository;

class Service
{
    private $repository_contenido;
    private $repository_visita;

    public function __construct(ContenidoRepository $repository_contenido, VisitaRepository $repository_visita)
    {
        $this->repository_contenido = $repository_contenido;
        $this->repository_visita = $repository_visita;
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
        $novedades['todo'] = $this->repository_contenido->findBy([], ['estreno' => 'DESC'], 12);
        $novedades['todo'] ? shuffle($novedades['todo']) : $novedades['todo'] = null;
        $novedades['peliculas'] =  $this->repository_contenido->findBy(['serie' => 0], ['estreno' => 'DESC'], 12);
        $novedades['series'] =  $this->repository_contenido->findBy(['serie' => 1], ['estreno' => 'DESC'], 12);
        $novedades['peliculas'] ? shuffle($novedades['peliculas']) : $novedades['peliculas'] = null;
        $novedades['series'] ? shuffle($novedades['series']) : $novedades['series'] = null;
        return $novedades;
    }
    public function getAllContent()
    {
        return $this->repository_contenido->findAll();
    }
}
