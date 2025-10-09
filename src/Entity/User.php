<?php

namespace App\Entity;

use App\Exception\InvalidTaxCodeException;
use App\Repository\UserRepository;
use App\Facade\TaxFacade;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\Column('id', type: 'uuid')]
    private Uuid $id;

    /**
     * @var string[] The user roles
     */
    #[ORM\Column('roles')]
    private array $roles;

    /**
     * @var string The hashed password
     */
    #[ORM\Column('password')]
    private string $password;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'purchaser')]
    private Collection $purchases;

    /**
     * @var Collection<int, Coupon>
     */
    #[ORM\OneToMany(targetEntity: Coupon::class, mappedBy: 'receiver')]
    private Collection $coupons;

    #[ORM\Column(length: 20, unique: true, nullable: true)]
    private ?string $taxCode = null;

    #[ORM\ManyToOne(Country::class, inversedBy: 'users')]
    #[ORM\JoinColumn('country_code', referencedColumnName: 'domain_zone', nullable: true, onDelete: 'RESTRICT')]
    private ?Country $country = null;

    /**
     * @param Uuid     $id
     * @param string   $password
     * @param ?Country $country
     * @param ?string  $taxCode
     * @param string[] $roles
     */
    public function __construct(
        Uuid $id,
        string $password,
        array $roles = ['ROLE_USER'],
    ) {
        $this->id = $id;
        $this->password = $password;
        $this->roles = $roles;
        $this->purchases = new ArrayCollection();
        $this->coupons = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->id;
    }

    public function addRole(string $role): static
    {
        $this->roles[] = $role;
        return $this->setRoles($this->roles);
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = array_unique($roles);
        if (!in_array(static::ROLE_USER, $this->roles)) {
            $this->roles[] = static::ROLE_USER;
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setPurchaser($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getPurchaser() === $this) {
                $purchase->setPurchaser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): static
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->setReceiver($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): static
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getReceiver() === $this) {
                $coupon->setReceiver(null);
            }
        }

        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(?string $taxCode): static
    {
        if ($taxCode !== null && $this->country !== null) {
            if (!TaxFacade::validateTaxCode($this->country, $taxCode)) {
                throw new InvalidTaxCodeException("Tax code must follow country-specific pattern");
            }
        }

        $this->taxCode = $taxCode;
        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function nullCountry(): static
    {
        $this->country = null;
        return $this;
    }

    public function setCountry(
        Country $country,
        string $taxCode,
    ): static {
        if (!TaxFacade::validateTaxCode($country, $taxCode)) {
            throw new InvalidTaxCodeException("Tax code must follow country-specific pattern");
        }

        $this->country = $country;
        $this->taxCode = $taxCode;

        return $this;
    }
}
