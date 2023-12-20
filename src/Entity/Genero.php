<?php

namespace App\Entity;

use App\Repository\GeneroRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeneroRepository::class)]
class Genero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cod_genero = null;

    #[ORM\ManyToOne]
    private ?Generos $genero = null;

    #[ORM\ManyToOne]
    private ?Contenido $contenido = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodGenero(): ?int
    {
        return $this->cod_genero;
    }

    public function setCodGenero(int $cod_genero): static
    {
        $this->cod_genero = $cod_genero;

        return $this;
    }

    public function getGenero(): ?Generos
    {
        return $this->genero;
    }

    public function setGenero(?Generos $genero): static
    {
        $this->genero = $genero;

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
