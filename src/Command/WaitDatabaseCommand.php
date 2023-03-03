<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'db:wait',
    description: 'Waits for database availability.',
)]
class WaitDatabaseCommand extends Command
{
    private const WAIT_SLEEP_TIME = 2;

    /**
     * Constructor
     *
     * @throws LogicException
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 0; $i < 60; $i += self::WAIT_SLEEP_TIME) {
            try {
                $connection = $this->em->getConnection();
                $statement = $connection->prepare('SHOW TABLES');
                $statement->executeQuery();
                $output->writeln('<info>Connection to the database is ok!</info>');

                return 0;
            } catch (Throwable) {
                $output->writeln('<comment>Trying to connect to the database seconds:' . $i . '</comment>');
                sleep(self::WAIT_SLEEP_TIME);

                continue;
            }
        }

        $output->writeln('<error>Can not connect to the database</error>');

        return 1;
    }
}
