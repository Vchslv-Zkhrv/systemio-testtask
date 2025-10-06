<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\SaleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV7;

class AppFixtures extends Fixture
{
    public function __construct(protected UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @return array<string,Country>
     */
    public function loadCountries(ObjectManager $manager): array
    {
        $germany = new Country('de', '/^DE\d{9}$/', 'Germany');
        $italy = new Country('it', '/^IT\d{11}$/', 'Italy');
        $greece = new Country('gr', '/^GR\d{9}$/', 'Greece');
        $france = new Country('fr', '/^FR[A-Z]{2}\d{9}$/', 'France');

        $manager->persist($germany);
        $manager->persist($italy);
        $manager->persist($greece);
        $manager->persist($france);

        $manager->flush();

        return [
            'de' => $germany,
            'it' => $italy,
            'gr' => $greece,
            'fr' => $france,
        ];
    }

    public function loadProducts(ObjectManager $manager): void
    {
        $iphone = new Product(343546, 'iphone', 2000, 1000);
        $headphones = new Product(81222, 'headphones', 20, 0);
        $case = new Product(9912144, 'case', 50, 100);

        $manager->persist($iphone);
        $manager->persist($headphones);
        $manager->persist($case);

        $manager->flush();
    }

    /**
     * @param array<string,Country> $countries
     *
     * @return array<string,User>
     */
    public function loadUsers(
        ObjectManager $manager,
        array $countries,
    ): array {
        $taxCodes = [
            'de' => 'DE123456789',
            'it' => 'IT01234567890',
            'gr' => 'GR123456789',
            'fr' => 'FRAB123456789',
        ];

        $users = [];

        foreach ($countries as $ccode => $country) {
            $plainPassword = 'PatriotOf' . $country->getName();
            $id = new UuidV7();
            $user = new User($country, $id, '', $taxCodes[$ccode]);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[$ccode] = $user;

            $token = base64_encode("$id:$plainPassword");
            echo "Registered new user for {$country->getName()}. Authorization header: `Basic $token`\n";
        }

        $manager->flush();
        return $users;
    }

    /**
     * @param User[] $users
     */
    public function loadCoupons(
        ObjectManager $manager,
        array $users,
    ): void {
        $coupons = [];

        foreach ($users as $user) {
            $coupons[] = (new Coupon(
                id: new UuidV7(),
                saleType: SaleType::PERCENT,
                saleValue: 10,
                code: "P10",
                validTill: new \DateTimeImmutable('now')
            ))->setReceiver($user);

            $coupons[] = (new Coupon(
                id: new UuidV7(),
                saleType: SaleType::PERCENT,
                saleValue: 50,
                code: "P20",
            ))->setReceiver($user);

            $coupons[] = (new Coupon(
                id: new UuidV7(),
                saleType: SaleType::EXACT,
                saleValue: 100,
                code: "E100",
                validTill: new \DateTimeImmutable('+3 days')
            ))->setReceiver($user);
        }

        foreach ($coupons as $coupon) {
            $manager->persist($coupon);
            echo "Coupon {$coupon->getCode()} granted to {$user->getId()}\n";
        }

        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {
        $countries = $this->loadCountries($manager);
        $this->loadProducts($manager);
        $users = $this->loadUsers($manager, $countries);
        $this->loadCoupons($manager, $users);
    }
}
