<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\UsuarioRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Deprecated;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'Já existe uma conta com este e-mail')]
class Usuario extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Informe o nome completo.')]
    private ?string $nome = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    protected ?DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, Arquivo>
     */
    #[ORM\OneToMany(targetEntity: Arquivo::class, mappedBy: 'usuario', orphanRemoval: true)]
    private Collection $arquivos;

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Apostador>
     */
    #[ORM\OneToMany(targetEntity: Apostador::class, mappedBy: 'usuario', orphanRemoval: true)]
    private Collection $apostadores;

    public function __construct()
    {
        $this->arquivos = new ArrayCollection();
        $this->apostadores = new ArrayCollection();
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Arquivo>
     */
    public function getArquivos(): Collection
    {
        return $this->arquivos;
    }

    public function addArquivo(Arquivo $arquivo): static
    {
        if (!$this->arquivos->contains($arquivo)) {
            $this->arquivos->add($arquivo);
            $arquivo->setUsuario($this);
        }

        return $this;
    }

    public function removeArquivo(Arquivo $arquivo): static
    {
        if ($this->arquivos->removeElement($arquivo)) {
            // set the owning side to null (unless already changed)
            if ($arquivo->getUsuario() === $this) {
                $arquivo->setUsuario(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Apostador>
     */
    public function getApostadores(): Collection
    {
        return $this->apostadores;
    }

    public function addApostadore(Apostador $apostadore): static
    {
        if (!$this->apostadores->contains($apostadore)) {
            $this->apostadores->add($apostadore);
            $apostadore->setUsuario($this);
        }

        return $this;
    }

    public function removeApostadore(Apostador $apostadore): static
    {
        if ($this->apostadores->removeElement($apostadore)) {
            // set the owning side to null (unless already changed)
            if ($apostadore->getUsuario() === $this) {
                $apostadore->setUsuario(null);
            }
        }

        return $this;
    }
}
