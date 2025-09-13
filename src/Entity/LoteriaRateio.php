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

use App\Repository\LoteriaRateioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LoteriaRateioRepository::class)]
class LoteriaRateio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'loteriaRateios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Loteria $loteria = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantidadeDezenasJogadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantidadeDezenasAcertadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantidadeDezenasPremiadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantidadePremios = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantidadeDezenasJogadas(): ?int
    {
        return $this->quantidadeDezenasJogadas;
    }

    public function setQuantidadeDezenasJogadas(int $quantidadeDezenasJogadas): static
    {
        $this->quantidadeDezenasJogadas = $quantidadeDezenasJogadas;

        return $this;
    }

    public function getQuantidadeDezenasAcertadas(): ?int
    {
        return $this->quantidadeDezenasAcertadas;
    }

    public function setQuantidadeDezenasAcertadas(int $quantidadeDezenasAcertadas): static
    {
        $this->quantidadeDezenasAcertadas = $quantidadeDezenasAcertadas;

        return $this;
    }

    public function getQuantidadeDezenasPremiadas(): ?int
    {
        return $this->quantidadeDezenasPremiadas;
    }

    public function setQuantidadeDezenasPremiadas(int $quantidadeDezenasPremiadas): static
    {
        $this->quantidadeDezenasPremiadas = $quantidadeDezenasPremiadas;

        return $this;
    }

    public function getQuantidadePremios(): ?int
    {
        return $this->quantidadePremios;
    }

    public function setQuantidadePremios(int $quantidadePremios): static
    {
        $this->quantidadePremios = $quantidadePremios;

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
}
