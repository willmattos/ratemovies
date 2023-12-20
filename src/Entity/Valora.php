<?php

namespace App\Entity;

use App\Repository\ValoraRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValoraRepository::class)]
class Valora
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $puntuacion = null;

    #[ORM\Column]
    private ?int $cod_contenido = null;

    #[ORM\Column]
    private ?int $cod_usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(?int $puntuacion): static
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    public function getCodContenido(): ?int
    {
        return $this->cod_contenido;
    }

    public function setCodContenido(int $cod_contenido): static
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
}
