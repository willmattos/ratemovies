<?php

namespace App\Entity;

use App\Repository\ContenidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $estreno = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portada = null;

    #[ORM\Column(nullable: true)]
    private ?int $serie = null;

    #[ORM\OneToMany(mappedBy: 'contenido', targetEntity: GeneroContenido::class)]
    private Collection $generos;

    
    #[ORM\OneToMany(mappedBy: 'contenido', targetEntity: Reparto::class)]
    private Collection $reparto;
    
    #[ORM\OneToMany(mappedBy: 'contenido', targetEntity: Valora::class)]
    private Collection $valoraciones;
    
    #[ORM\OneToMany(mappedBy: 'contenido', targetEntity: Critica::class)]
    private Collection $criticas;
    
    private $ownlike;

    public function __construct()
    {
        $this->generos = new ArrayCollection();
        $this->reparto = new ArrayCollection();
        $this->valoraciones = new ArrayCollection();
        $this->criticas = new ArrayCollection();
    }
   

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
     * @return Collection<int, Genero>
     */
    public function getGeneros(): Collection
    {
        return $this->generos;
    }

    // public function addGenero(GeneroContenido $genero): static
    // {
    //     if (!$this->generos->contains($genero)) {
    //         $this->generos->add($genero);
    //         $genero->setContenido($this);
    //     }

    //     return $this;
    // }

    // public function removeGenero(GeneroContenido $genero): static
    // {
    //     if ($this->generos->removeElement($genero)) {
    //         // set the owning side to null (unless already changed)
    //         if ($genero->getContenido() === $this) {
    //             $genero->setContenido(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Reparto>
     */
    public function getReparto(): Collection
    {
        return $this->reparto;
    }

    public function addReparto(Reparto $reparto): static
    {
        if (!$this->reparto->contains($reparto)) {
            $this->reparto->add($reparto);
            $reparto->setContenido($this);
        }

        return $this;
    }

    public function removeReparto(Reparto $reparto): static
    {
        if ($this->reparto->removeElement($reparto)) {
            // set the owning side to null (unless already changed)
            if ($reparto->getContenido() === $this) {
                $reparto->setContenido(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Valora>
     */
    public function getValoraciones(): Collection
    {
        return $this->valoraciones;
    }

    public function addValoracione(Valora $valoracione): static
    {
        if (!$this->valoraciones->contains($valoracione)) {
            $this->valoraciones->add($valoracione);
            $valoracione->setContenido($this);
        }

        return $this;
    }

    public function removeValoracione(Valora $valoracione): static
    {
        if ($this->valoraciones->removeElement($valoracione)) {
            // set the owning side to null (unless already changed)
            if ($valoracione->getContenido() === $this) {
                $valoracione->setContenido(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Critica>
     */
    public function getCriticas(): Collection
    {
        return $this->criticas;
    }

    public function addCritica(Critica $critica): static
    {
        if (!$this->criticas->contains($critica)) {
            $this->criticas->add($critica);
            $critica->setContenido($this);
        }

        return $this;
    }

    public function removeCritica(Critica $critica): static
    {
        if ($this->criticas->removeElement($critica)) {
            // set the owning side to null (unless already changed)
            if ($critica->getContenido() === $this) {
                $critica->setContenido(null);
            }
        }

        return $this;
    }


}
