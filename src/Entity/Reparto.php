<?php

namespace App\Entity;

use App\Repository\RepartoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepartoRepository::class)]
class Reparto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reparto')]
    private ?Contenido $contenido = null;

    #[ORM\ManyToOne]
    private ?Actor $actor = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): static
    {
        $this->actor = $actor;

        return $this;
    }
}
