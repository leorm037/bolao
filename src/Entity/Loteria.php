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
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LoteriaRepository::class)]
#[ORM\Cache(usage: 'READ_ONLY')]
#[ORM\UniqueConstraint(name: 'uuid_UNIQUE', columns: ['uuid'])]
#[ORM\UniqueConstraint(name: 'nome_UNIQUE', columns: ['nome'])]
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

    #[ORM\Column(type: Types::JSON)]
    private array $dezenas = [];

    #[ORM\Column(type: Types::JSON)]
    private array $apostas = [];

    #[ORM\Column(type: Types::JSON)]
    private array $premios = [];

    /**
     * @var Collection<int, LoteriaRateio>
     */
    #[ORM\OneToMany(targetEntity: LoteriaRateio::class, mappedBy: 'loteria', orphanRemoval: true)]
    #[ORM\Cache(usage: 'READ_ONLY')]
    private Collection $loteriaRateios;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTime $updatedAt = null;

    /**
     * @var Collection<int, Concurso>
     */
    #[ORM\OneToMany(targetEntity: Concurso::class, mappedBy: 'loteria')]
    private Collection $concursos;

    public function __construct()
    {
        $this->loteriaRateios = new ArrayCollection();
        $this->concursos = new ArrayCollection();
    }

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

    public function getPremios(): array
    {
        return $this->premios;
    }

    public function setPremios(array $premios): static
    {
        $this->premios = $premios;

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

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, LoteriaRateio>
     */
    public function getLoteriaRateios(): Collection
    {
        return $this->loteriaRateios;
    }

    public function addLoteriaRateio(LoteriaRateio $loteriaRateio): static
    {
        if (!$this->loteriaRateios->contains($loteriaRateio)) {
            $this->loteriaRateios->add($loteriaRateio);
            $loteriaRateio->setLoteria($this);
        }

        return $this;
    }

    public function removeLoteriaRateio(LoteriaRateio $loteriaRateio): static
    {
        if ($this->loteriaRateios->removeElement($loteriaRateio)) {
            // set the owning side to null (unless already changed)
            if ($loteriaRateio->getLoteria() === $this) {
                $loteriaRateio->setLoteria(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Concurso>
     */
    public function getConcursos(): Collection
    {
        return $this->concursos;
    }

    public function addConcurso(Concurso $concurso): static
    {
        if (!$this->concursos->contains($concurso)) {
            $this->concursos->add($concurso);
            $concurso->setLoteria($this);
        }

        return $this;
    }

    public function removeConcurso(Concurso $concurso): static
    {
        if ($this->concursos->removeElement($concurso)) {
            // set the owning side to null (unless already changed)
            if ($concurso->getLoteria() === $this) {
                $concurso->setLoteria(null);
            }
        }

        return $this;
    }
}
