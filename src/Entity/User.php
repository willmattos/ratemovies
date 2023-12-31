<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?int $rol;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $recuperar = null;

    #[ORM\Column]
    private ?int $activado = null;

    #[ORM\Column]
    private ?int $bloquear = null;

    #[ORM\Column(nullable: true)]
    private ?string $foto;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Critica::class)]
    private Collection $criticas;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favorito::class)]
    private Collection $favoritos;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: Siguiendo::class)]
    private Collection $siguiendo;

    #[ORM\OneToMany(mappedBy: 'following', targetEntity: Siguiendo::class)]
    private Collection $seguidores;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $caducidad = null;


    public function __construct()
    {
        $this->criticas = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
        $this->siguiendo = new ArrayCollection();
        $this->seguidores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        switch ($this->getRol()) {
            case 1:
                $roles[] = 'ROLE_ADMIN';
                break;
            case 2:
                $roles[] = 'ROLE_ADMIN';
                $roles[] = 'ROLE_SUPERADMIN';
                break;
            default:
                $roles[] = 'ROLE_USER';
                break;
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of recuperar
     */
    public function getRecuperar()
    {
        return $this->recuperar;
    }

    /**
     * Set the value of recuperar
     *
     * @return  self
     */
    public function setRecuperar($recuperar)
    {
        $this->recuperar = $recuperar;

        return $this;
    }

    /**
     * Get the value of activado
     */
    public function getActivado()
    {
        return $this->activado;
    }

    /**
     * Set the value of activado
     *
     * @return  self
     */
    public function setActivado($activado)
    {
        $this->activado = $activado;

        return $this;
    }

    /**
     * Get the value of foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

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
            $critica->setUser($this);
        }

        return $this;
    }

    public function removeCritica(Critica $critica): static
    {
        if ($this->criticas->removeElement($critica)) {
            // set the owning side to null (unless already changed)
            if ($critica->getUser() === $this) {
                $critica->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorito>
     */
    public function getFavoritos(): Collection
    {
        return $this->favoritos;
    }

    public function addFavorito(Favorito $favorito): static
    {
        if (!$this->favoritos->contains($favorito)) {
            $this->favoritos->add($favorito);
            $favorito->setUser($this);
        }

        return $this;
    }

    public function removeFavorito(Favorito $favorito): static
    {
        if ($this->favoritos->removeElement($favorito)) {
            // set the owning side to null (unless already changed)
            if ($favorito->getUser() === $this) {
                $favorito->setUser(null);
            }
        }

        return $this;
    }

    public function getBloquear(): ?int
    {
        return $this->bloquear;
    }

    public function setBloquear(int $bloquear): static
    {
        $this->bloquear = $bloquear;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Siguiendo>
     */
    public function getSiguiendo(): Collection
    {
        return $this->siguiendo;
    }

    public function addSiguiendo(Siguiendo $siguiendo): static
    {
        if (!$this->siguiendo->contains($siguiendo)) {
            $this->siguiendo->add($siguiendo);
            $siguiendo->setFollower($this);
        }

        return $this;
    }

    public function removeSiguiendo(Siguiendo $siguiendo): static
    {
        if ($this->siguiendo->removeElement($siguiendo)) {
            // set the owning side to null (unless already changed)
            if ($siguiendo->getFollower() === $this) {
                $siguiendo->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Siguiendo>
     */
    public function getSeguidores(): Collection
    {
        return $this->seguidores;
    }

    public function addSeguidore(Siguiendo $seguidore): static
    {
        if (!$this->seguidores->contains($seguidore)) {
            $this->seguidores->add($seguidore);
            $seguidore->setFollowing($this);
        }

        return $this;
    }

    public function removeSeguidore(Siguiendo $seguidore): static
    {
        if ($this->seguidores->removeElement($seguidore)) {
            // set the owning side to null (unless already changed)
            if ($seguidore->getFollowing() === $this) {
                $seguidore->setFollowing(null);
            }
        }

        return $this;
    }

    public function getCaducidad(): ?\DateTimeInterface
    {
        return $this->caducidad;
    }

    public function setCaducidad(?\DateTimeInterface $caducidad): static
    {
        $this->caducidad = $caducidad;

        return $this;
    }

}
