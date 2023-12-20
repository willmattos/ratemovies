<?php

namespace App\Entity;

use App\Repository\FavoritoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritoRepository::class)]
class Favorito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_contenido = null;

    #[ORM\Column]
    private ?int $cod_usuario = null;

    #[ORM\ManyToOne]
    private ?Contenido $contenido = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodContenido(): ?int
    {
        return $this->cod_contenido;
    }

    public function setCodContenido(?int $cod_contenido): static
    {
        $this->cod_contenido = $cod_contenido;

        return $this;
    }

    public function getCodUsuario(): ?int
    {
        return $this->cod_usuario;
    }

    public function setCodUsuario(int $cod_usuario): static
    {
        $this->cod_usuario = $cod_usuario;

        return $this;
    }

    public function getContenido(): ?Contenido
    {
        return $this->contenido;
    }

    public function setContenido(?Contenido $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }
}
