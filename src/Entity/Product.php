<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['step_2'])]
    #[Assert\Length(min: 3, groups: ['step_2'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['step_2'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive(groups: ['step_4'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['step_1'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['step_2'])]
    private ?string $marque = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(groups: ['step_3'])]
    private ?int $stock = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }
}
