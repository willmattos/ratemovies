<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_critica = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_comentario = null;

    #[ORM\Column(nullable: true)]
    private ?int $cod_usuario = null;

    #[ORM\ManyToOne]
    private ?User $usuario_objeto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodCritica(): ?int
    {
        return $this->cod_critica;
    }

    public function setCodCritica(int $cod_critica): static
    {
        $this->cod_critica = $cod_critica;

        return $this;
    }

    public function getCodComentario(): ?int
    {
        return $this->cod_comentario;
    }

    public function setCodComentario(?int $cod_comentario): static
    {
        $this->cod_comentario = $cod_comentario;

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

    public function getUsuarioObjeto(): ?User
    {
        return $this->usuario_objeto;
    }

    public function setUsuarioObjeto(?User $usuario_objeto): static
    {
        $this->usuario_objeto = $usuario_objeto;

        return $this;
    }
}
