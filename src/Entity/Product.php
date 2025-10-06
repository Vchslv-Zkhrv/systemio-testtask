<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('products')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Index('id_product_name', ['name'])]
class Product
{
    #[ORM\Id]
    #[ORM\Column('article')]
    private int $article;

    #[ORM\Column('name', length: 255, nullable: false)]
    private string $name;

    /**
     * price in EUR
     */
    #[ORM\Column]
    private float $price;

    #[ORM\Column]
    private int $stock;

    public function __construct(
        int $article,
        string $name,
        float $price,
        int $stock = 0,
    ) {
        $this->article = $article;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }
}
