<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Table('countries')]
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    /**
     * Country domain zone
     *
     * @see https://wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    #[ORM\Id]
    #[ORM\Column('domain_zone', length: 2)]
    private string $domainZone;

    /**
     * Tax code pattern
     */
    #[ORM\Column('tax_code_pattern', length: 255, unique: true)]
    private string $taxCodePattern;

    /**
     * Country name in english
     */
    #[ORM\Column('name', length: 255, unique: true)]
    private string $name;

    /**
     * @var Collection<Product>
     */
    #[ORM\OneToMany(Product::class, mappedBy: 'country')]
    private Collection $products;

    public function __construct(
        string $domainZone,
        string $taxCodePattern,
        string $name,
    ) {
        $this->domainZone = $domainZone;
        $this->taxCodePattern = $taxCodePattern;
        $this->name = $name;
    }

    public function getDomainZone(): string
    {
        return $this->domainZone;
    }

    public function getTaxCodePattern(): string
    {
        return $this->taxCodePattern;
    }

    public function setTaxCodePattern(string $taxCodePattern): static
    {
        $this->taxCodePattern = $taxCodePattern;
        return $this;
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
