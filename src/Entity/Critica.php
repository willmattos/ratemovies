<?php

namespace App\Entity;

use App\Repository\CriticaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CriticaRepository::class)]
class Critica
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comentario = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_contenido = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_usuario = null;

    #[ORM\ManyToOne]
    private ?User $usuario = null;

    #[ORM\ManyToOne]
    private ?Contenido $contenido = null;

    private $comentarios;
    private $likes;
    private $ownlike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
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

    public function setCodUsuario(?int $cod_usuario): static
    {
        $this->cod_usuario = $cod_usuario;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

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

    /**
     * Get the value of comentarios
     */ 
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set the value of comentarios
     *
     * @return  self
     */ 
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get the value of likes
     */ 
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set the value of likes
     *
     * @return  self
     */ 
    public function setLikes($likes)
    {
        $this->likes = $likes;

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
}
