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

use App\Repository\LoteriaRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LoteriaRepository::class)]
#[ORM\UniqueConstraint(name: 'uuid_UNIQUE', columns: ['uuid'])]
#[ORM\UniqueConstraint(name: 'nome_UNIQUE', columns: ['nome'])]
#[ORM\UniqueConstraint(name: 'uuid_UNIQUE', columns: ['uuid'])]
#[ORM\UniqueConstraint(name: 'slug_UNIQUE', columns: ['url_slug'])]
#[ORM\HasLifecycleCallbacks]
class Loteria extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    protected ?Uuid $uuid = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Informe o nome da Loteria.')]
    private ?string $nome = null;

    #[ORM\Column(length: 60)]
    protected ?string $urlSlug = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Informe a URL da API.')]
    private ?string $urlApi = null;

    #[ORM\Column]
    private array $dezenas = [];

    #[ORM\Column]
    private array $apostas = [];

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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

    public function getUrlSlug(): ?string
    {
        return $this->urlSlug;
    }

    public function setUrlSlug(string $urlSlug): static
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    public function getUrlApi(): ?string
    {
        return $this->urlApi;
    }

    public function setUrlApi(string $urlApi): static
    {
        $this->urlApi = $urlApi;

        return $this;
    }

    public function getDezenas(): array
    {
        return $this->dezenas;
    }

    public function setDezenas(array $dezenas): static
    {
        $this->dezenas = $dezenas;

        return $this;
    }

    public function getApostas(): array
    {
        return $this->apostas;
    }

    public function setApostas(array $apostas): static
    {
        $this->apostas = $apostas;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
