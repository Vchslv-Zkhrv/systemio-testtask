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
        echo "- - - - - Loading countries - - - - -\n";

        $germany = new Country('de', '/^DE\d{9}$/', 'Germany');
        echo "- Germany\n";

        $italy = new Country('it', '/^IT\d{11}$/', 'Italy');
        echo "- Italy\n";

        $greece = new Country('gr', '/^GR\d{9}$/', 'Greece');
        echo "- Greece\n";

        $france = new Country('fr', '/^FR[A-Z]{2}\d{9}$/', 'France');
        echo "- France\n";

        $manager->persist($germany);
        $manager->persist($italy);
        $manager->persist($greece);
        $manager->persist($france);

        $manager->flush();
        echo "\n";

        return [
            'de' => $germany,
            'it' => $italy,
            'gr' => $greece,
            'fr' => $france,
        ];
    }

    public function loadProducts(ObjectManager $manager): void
    {
        echo "- - - - - Loading products - - - - -\n";

        $iphone = new Product(343546, 'iphone', 2000, 1000);
        echo "- iphone: 343546\n";

        $headphones = new Product(81222, 'headphones', 20, 0);
        echo "- headphones: 81222\n";

        $case = new Product(9912144, 'case', 50, 100);
        echo "- case: 9912144\n";

        $manager->persist($iphone);
        $manager->persist($headphones);
        $manager->persist($case);

        $manager->flush();
        echo "\n";
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
        echo "- - - - - Loading users - - - - -\n";

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
            $user = new User(id: $id, password: '');
            $taxCode = $taxCodes[$ccode];
            $user->setCountry($country,  $taxCodes[$ccode]);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[$ccode] = $user;
            echo "- {$country->getName()} user: $taxCode\n";
        }

        $plainPassword = 'root';
        $id = new UuidV7();
        $admin = new User(id: $id, password: '');
        $admin->addRole(User::ROLE_ADMIN);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $plainPassword);
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $token = base64_encode("$id:$plainPassword");
        echo "- admin. Authorization header: `Basic $token`\n";

        $manager->flush();
        echo "\n";

        return $users;
    }

    /**
     * @param User[] $users
     */
    public function loadCoupons(
        ObjectManager $manager,
        array $users,
    ): void {
        echo "- - - - - Loading coupons - - - - -\n";
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
                code: "P50",
                validTill: null,
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
            $receiver = $coupon->getReceiver();
            $country = $receiver?->getCountry();
            if ($receiver && $country) {
                echo "- {$coupon->getCode()} granted to {$country->getName()} user\n";
            }
        }

        $manager->flush();
        echo "\n";
    }

    public function load(ObjectManager $manager): void
    {
        $countries = $this->loadCountries($manager);
        $this->loadProducts($manager);
        $users = $this->loadUsers($manager, $countries);
        $this->loadCoupons($manager, $users);
    }
}
