<?php

namespace App\Entity;

use App\Repository\SiguiendoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiguiendoRepository::class)]
class Siguiendo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $usuario = null;

    #[ORM\Column]
    private ?int $siguiendo = null;

    #[ORM\ManyToOne]
    private ?User $follower = null;

    #[ORM\ManyToOne]
    private ?User $following = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSiguiendo(): ?int
    {
        return $this->siguiendo;
    }

    public function setSiguiendo(int $siguiendo): static
    {
        $this->siguiendo = $siguiendo;

        return $this;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): static
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowing(): ?User
    {
        return $this->following;
    }

    public function setFollowing(?User $following): static
    {
        $this->following = $following;

        return $this;
    }
}
