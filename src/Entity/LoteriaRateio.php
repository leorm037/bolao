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
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: LoteriaRateioRepository::class)]
#[ORM\Cache(usage: 'READ_WRITE', region: 'read_write')]
#[ORM\HasLifecycleCallbacks]
class LoteriaRateio extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'loteriaRateios')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Informe a Loteria.')]
    private ?Loteria $loteria = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'Informe a quantidade de dezenas jogadas.')]
    private ?int $quantidadeDezenasJogadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'Informe a quantidade de dezenas acertadas.')]
    private ?int $quantidadeDezenasAcertadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'Informe a quantidade de dezenas premiadas.')]
    private ?int $quantidadeDezenasPremiadas = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'Informe a quantidade de prêmios.')]
    private ?int $quantidadePremios = null;

    #[ORM\Column(type: 'uuid')]
    protected ?Uuid $uuid = null;

    #[ORM\Column]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    protected ?DateTime $updatedAt = null;

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

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->quantidadeDezenasAcertadas > $this->quantidadeDezenasJogadas) {
            $context->buildViolation('A quantidade de dezenas acertadas não pode ser maior que a quantidade de dezenas jogadas.')
                    ->atPath('quantidadeDezenasAcertadas')
                    ->addViolation()
            ;
        }

        if (!\in_array($this->quantidadeDezenasPremiadas, $this->loteria->getPremios())) {
            $context->buildViolation('A quantidade de dezenas premiadas deve estar dentro do intervalo informado na Loteria')
                    ->atPath('quantidadeDezenasPremiadas')
                    ->addViolation()
            ;
        }
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
}
