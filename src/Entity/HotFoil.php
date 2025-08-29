<?php

namespace App\Entity;

use App\Repository\HotFoilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotFoilRepository::class)]
class HotFoil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $setup_cost = null;

    #[ORM\Column]
    private ?float $per_quantity_multiplier = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPerQuantityMultiplier(): ?float
    {
        return $this->per_quantity_multiplier;
    }

    public function setPerQuantityMultiplier(float $per_quantity_multiplier): static
    {
        $this->per_quantity_multiplier = $per_quantity_multiplier;

        return $this;
    }
}
