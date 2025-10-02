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

use App\Repository\ConcursoRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConcursoRepository::class)]
class Concurso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(nullable: true)]
    private ?array $dezenas = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $apuracao = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $municipio = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $uf = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $local = null;

    #[ORM\ManyToOne(inversedBy: 'concursos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Loteria $loteria = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $updatedAt = null;

    /**
     * @var Collection<int, Bolao>
     */
    #[ORM\OneToMany(targetEntity: Bolao::class, mappedBy: 'concurso')]
    private Collection $boloes;

    public function __construct()
    {
        $this->boloes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDezenas(): ?array
    {
        return $this->dezenas;
    }

    public function setDezenas(?array $dezenas): static
    {
        $this->dezenas = $dezenas;

        return $this;
    }

    public function getApuracao(): ?DateTime
    {
        return $this->apuracao;
    }

    public function setApuracao(?DateTime $apuracao): static
    {
        $this->apuracao = $apuracao;

        return $this;
    }

    public function getMunicipio(): ?string
    {
        return $this->municipio;
    }

    public function setMunicipio(?string $municipio): static
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(?string $uf): static
    {
        $this->uf = $uf;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(?string $local): static
    {
        $this->local = $local;

        return $this;
    }

    public function getLoteria(): ?Loteria
    {
        return $this->loteria;
    }

    public function setLoteria(?Loteria $loteria): static
    {
        $this->loteria = $loteria;

        return $this;
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
     * @return Collection<int, Bolao>
     */
    public function getBoloes(): Collection
    {
        return $this->boloes;
    }

    public function addBolo(Bolao $bolo): static
    {
        if (!$this->boloes->contains($bolo)) {
            $this->boloes->add($bolo);
            $bolo->setConcurso($this);
        }

        return $this;
    }

    public function removeBolo(Bolao $bolo): static
    {
        if ($this->boloes->removeElement($bolo)) {
            // set the owning side to null (unless already changed)
            if ($bolo->getConcurso() === $this) {
                $bolo->setConcurso(null);
            }
        }

        return $this;
    }
}
