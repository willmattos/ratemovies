<?php

namespace App\Entity;

use App\Repository\GeneroContenidoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeneroContenidoRepository::class)]
class GeneroContenido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Generos $genero = null;

    #[ORM\ManyToOne(inversedBy: 'generos')]
    private ?Contenido $contenido = null;

  
    public function getId(): ?int
    {
        return $this->id;
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
