<?php

namespace App\Entity;

use App\Repository\PrintingMethodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrintingMethodRepository::class)]
class PrintingMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $setup_cost = null;

    #[ORM\Column]
    private ?float $per_color_multiplier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSetupCost(): ?float
    {
        return $this->setup_cost;
    }

    public function setSetupCost(float $setup_cost): static
    {
        $this->setup_cost = $setup_cost;

        return $this;
    }

    public function getPerColorMultiplier(): ?float
    {
        return $this->per_color_multiplier;
    }

    public function setPerColorMultiplier(float $per_color_multiplier): static
    {
        $this->per_color_multiplier = $per_color_multiplier;

        return $this;
    }
}
