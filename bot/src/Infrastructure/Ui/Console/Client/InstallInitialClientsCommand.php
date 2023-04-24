<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Console\Client;


use Bot\Domain\Client\Model\Client;
use Bot\Domain\Shared\Model\Value\Email;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Domain\Shared\Model\IdentityGenerator;


class InstallInitialClientsCommand extends Command
{
    protected static $defaultName = 'app:install-initial-clients';

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly IdentityGenerator $identityGenerator,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setDescription('Install initial Clients');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Init',
            '============',
            '',
        ]);

        $clientA = new Client(
            new Uuid($this->identityGenerator->generateId()),
            new Email('clientA@bot.com'),
            new Name('Cliente A')
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            $clientA,
            'client_a_password'
        );

        $clientA->setPassword($hashedPassword);
        $this->clientRepository->save($clientA);

        $output->writeln([
            'ClientA Created: '. $clientA->getId()->value,
            '============',
            '',
        ]);

        $clientB = new Client(
            new Uuid($this->identityGenerator->generateId()),
            new Email('clientB@bot.com'),
            new Name('Cliente B')
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            $clientB,
            'client_b_password'
        );

        $clientB->setPassword($hashedPassword);
        $this->clientRepository->save($clientB);

        $output->writeln([
            'ClientB Created: '. $clientB->getId()->value,
            '============',
            '',
        ]);

        $clientC = new Client(
            new Uuid($this->identityGenerator->generateId()),
            new Email('clientC@bot.com'),
            new Name('Cliente C')
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            $clientC,
            'client_c_password'
        );

        $clientC->setPassword($hashedPassword);
        $this->clientRepository->save($clientC);

        $output->writeln([
            'ClientC Created: '. $clientC->getId()->value,
            '============',
            '',
        ]);



        return Command::SUCCESS;
    }



}