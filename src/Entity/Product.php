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
    #[ORM\Column('tax_number', length: 255, nullable: false)]
    private string $taxNumber;

    #[ORM\Column('name', length: 255, nullable: false)]
    private string $name;

    #[ORM\ManyToOne(Country::class, inversedBy: 'products')]
    #[ORM\JoinColumn('country_code', referencedColumnName: 'domain_zone', nullable: false, onDelete: 'RESTRICT')]
    private Country $country;

    public function __construct(
        string $taxNumber,
        string $name,
    ) {
        $this->taxNumber = $taxNumber;
        $this->name = $name;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
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
}
