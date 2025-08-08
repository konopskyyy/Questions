<?php

namespace App\Command;

use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:make-admin',
    description: 'generate a new admin user',
)]
class MakeAdminCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');

        if (!$password || !$email) {
            $io->error('Please provide a password');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email)
            ->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->userRepository->save($user, true);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
