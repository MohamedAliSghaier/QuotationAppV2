<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{

    public function __construct()
{
    $this->createdAt = new \DateTimeImmutable();
    $this->status = 'draft';
    $this->totalPrice = '0.00';
}

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clientName = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Paper $paper = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrintingMethod $printingMethod = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    private ?Lamination $lamination = null;

    #[ORM\ManyToOne]
    private ?Corners $corners = null;

    #[ORM\ManyToOne]
    private ?Folding $folding = null;

    #[ORM\ManyToOne]
    private ?HotFoil $hotFoil = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $width = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $height = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPaper(): ?Paper
    {
        return $this->paper;
    }

    public function setPaper(?Paper $paper): static
    {
        $this->paper = $paper;

        return $this;
    }

    public function getPrintingMethod(): ?PrintingMethod
    {
        return $this->printingMethod;
    }

    public function setPrintingMethod(?PrintingMethod $printingMethod): static
    {
        $this->printingMethod = $printingMethod;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getLamination(): ?Lamination
    {
        return $this->lamination;
    }

    public function setLamination(?Lamination $lamination): static
    {
        $this->lamination = $lamination;

        return $this;
    }

    public function getCorners(): ?Corners
    {
        return $this->corners;
    }

    public function setCorners(?Corners $corners): static
    {
        $this->corners = $corners;

        return $this;
    }

    public function getFolding(): ?Folding
    {
        return $this->folding;
    }

    public function setFolding(?Folding $folding): static
    {
        $this->folding = $folding;

        return $this;
    }

    public function getHotFoil(): ?HotFoil
    {
        return $this->hotFoil;
    }

    public function setHotFoil(?HotFoil $hotFoil): static
    {
        $this->hotFoil = $hotFoil;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): static
    {
        $this->height = $height;

        return $this;
    }
}
