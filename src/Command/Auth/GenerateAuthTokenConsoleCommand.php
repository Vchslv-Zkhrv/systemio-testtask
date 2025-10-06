<?php

namespace App\Command\Auth;

use App\Entity\User;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:auth:generate-token',
    description: 'Generates & registers basic auth API token',
)]
class GenerateAuthTokenConsoleCommand extends Command
{
    public function __construct(
        protected CountryRepository $countryRepository,
        protected UserPasswordHasherInterface $passwordHasher,
        protected EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('countryCode', InputArgument::REQUIRED, 'Country domain zone code');
        $this->addArgument('taxCode', InputArgument::REQUIRED, 'Tax code');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countryCode = $input->getArgument('countryCode');
        $country = $this->countryRepository->find($countryCode);
        if ($country === null) {
            $io->error("Cannot find '$countryCode' country");
        }

        $taxCode = $input->getArgument('taxCode');

        $password = (string)$io->askHidden("Input password");
        if (strlen($password) < 6) {
            $io->error("Password is too short");
            return Command::INVALID;
        }

        $id = new UuidV7();
        $user = new User($country, $id, '', $taxCode);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $token = base64_encode("$id:$password");

        $io->success("New user registered: `$id`. The token is:");
        $io->text("Basic $token");

        return Command::SUCCESS;
    }
}
