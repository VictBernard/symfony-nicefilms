<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:AssignSuperAdmin',
    description: 'Assign super admin role to a user',
)]
class AssignSuperAdminCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('user', InputArgument::REQUIRED, 'Email of the user we want to promote');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $input->getArgument('user')]);

        if ($user) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $this->entityManager->flush();

            $output->writeln(sprintf('Super admin role assigned successfully to %s', $user->getEmail()));
        } else {
            $output->writeln('User not found.');
        }

        return Command::SUCCESS;
    }
}
