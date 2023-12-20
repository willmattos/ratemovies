<?php

namespace App\Entity;

use App\Repository\ContenidoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContenidoRepository::class)]
class Contenido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $estreno = null;

    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portada = null;

    #[ORM\Column(nullable: true)]
    private ?int $serie = null;

    private $generos;
    private $ownlike;
    private $reparto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getEstreno(): ?\DateTimeInterface
    {
        return $this->estreno;
    }

    public function setEstreno(\DateTimeInterface $estreno): static
    {
        $this->estreno = $estreno;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPortada(): ?string
    {
        return $this->portada;
    }

    public function setPortada(?string $portada): static
    {
        $this->portada = $portada;

        return $this;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getSerie(): ?int
    {
        return $this->serie;
    }

    public function setSerie(?int $serie): static
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get the value of reparto
     */ 
    public function getReparto()
    {
        return $this->reparto;
    }

    /**
     * Set the value of reparto
     *
     * @return  self
     */ 
    public function setReparto($reparto)
    {
        $this->reparto = $reparto;

        return $this;
    }

    /**
     * Get the value of ownlike
     */ 
    public function getOwnlike()
    {
        return $this->ownlike;
    }

    /**
     * Set the value of ownlike
     *
     * @return  self
     */ 
    public function setOwnlike($ownlike)
    {
        $this->ownlike = $ownlike;

        return $this;
    }

    /**
     * Get the value of generos
     */ 
    public function getGeneros()
    {
        return $this->generos;
    }

    /**
     * Set the value of generos
     *
     * @return  self
     */ 
    public function setGeneros($generos)
    {
        $this->generos = $generos;

        return $this;
    }
}
