<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'country')]
    private Collection $users;

    public function __construct(
        string $domainZone,
        string $taxCodePattern,
        string $name,
    ) {
        $this->domainZone = $domainZone;
        $this->taxCodePattern = $taxCodePattern;
        $this->name = $name;
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCountry($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCountry() === $this) {
                $user->setCountry(null);
            }
        }

        return $this;
    }
}
