<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Product;
use App\Entity\User;
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
        $headphones = new Product(81222, 'headphones', 20, 1);
        $case = new Product(9912144, 'case', 50, 100);

        $manager->persist($iphone);
        $manager->persist($headphones);
        $manager->persist($case);

        $manager->flush();
    }

    /**
     * @param array<string,Country> $countries
     */
    public function loadUsers(
        ObjectManager $manager,
        array $countries,
    ): void {

        $taxCodes = [
            'de' => 'DE123456789',
            'it' => 'IT01234567890',
            'gr' => 'GR123456789',
            'fr' => 'FRAB123456789',
        ];

        foreach ($countries as $ccode => $country) {
            $plainPassword = $country->getName() . 'Patriot';
            $id = new UuidV7();
            $user = new User($country, $id, '', $taxCodes[$ccode]);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);

            $token = base64_encode("$id:$plainPassword");
            echo "Registered new user for {$country->getName()}. Authorization header: `Basic $token`\n";
        }

        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {
        $countries = $this->loadCountries($manager);
        $this->loadProducts($manager);
        $this->loadUsers($manager, $countries);
    }
}
