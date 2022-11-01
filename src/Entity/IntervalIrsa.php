<?php

namespace App\Entity;

use App\Repository\IntervalIrsaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntervalIrsaRepository::class)
 */
class IntervalIrsa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $bareme_id;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $min;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     */
    private $max;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $pourcentage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaremeId(): ?int
    {
        return $this->bareme_id;
    }

    public function setBaremeId(int $bareme_id): self
    {
        $this->bareme_id = $bareme_id;

        return $this;
    }

    public function getMin(): ?string
    {
        return $this->min;
    }

    public function setMin(string $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?string
    {
        return $this->max;
    }

    public function setMax(?string $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getPourcentage(): ?string
    {
        return $this->pourcentage;
    }

    public function setPourcentage(string $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }
}
