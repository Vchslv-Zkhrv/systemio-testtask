<?php

namespace App\Command\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
        protected UserPasswordHasherInterface $passwordHasher,
        protected EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $password = (string)$io->askHidden("Input password");
        if (strlen($password) < 6) {
            $io->error("Password is too short");
            return Command::INVALID;
        }

        $id = new UuidV7();
        $user = new User($id, '');

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
