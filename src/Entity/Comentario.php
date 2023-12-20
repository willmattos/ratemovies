<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comentario = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $usuario = null;

    #[ORM\Column]
    private ?int $critica = null;

    private $likes;

    #[ORM\ManyToOne]
    private ?User $usuario_objeto = null;

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

    public function getUsuario(): ?int
    {
        return $this->usuario;
    }

    public function setUsuario(int $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCritica(): ?int
    {
        return $this->critica;
    }

    public function setCritica(int $critica): static
    {
        $this->critica = $critica;

        return $this;
    }



    public function getUsuarioObjeto(): ?User
    {
        return $this->usuario_objeto;
    }

    public function setUsuarioObjeto(?User $usuario_objeto): static
    {
        $this->usuario_objeto = $usuario_objeto;

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
}
