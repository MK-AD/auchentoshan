<?php

namespace App\Command;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportBlzCommand extends Command
{
    protected static $defaultName = 'app:import-blz';
    protected static $defaultDescription = 'Add a short description for your command';
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('url', InputArgument::REQUIRED, 'URL of the file from www.bundesbank.de')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        $io->comment($url);

        $conn = $this->entityManager->getConnection();

        $upsertQuery = "INSERT INTO `bank` (`id`, `bic`, `name`, `short_name`, `postal_code`, `locality`, " .
                       "`created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, now(), now()) ON DUPLICATE KEY UPDATE " .
                       "`bic` = ?, `name` = ?, `short_name` = ?, `postal_code` = ?, `locality` = ?, `updated_at` = " .
                       "now()";

        $rows = file($url);
        $problems = [];

        foreach ($rows as $row) {
            if (substr($row, 8, 1) === '1') {
                $bankCode = trim(substr($row, 0, 8));
                $name = utf8_encode(trim(substr($row, 9, 58)));
                $postalCode = trim(substr($row, 67, 5));
                $locality = utf8_encode(trim(substr($row, 72, 35)));
                $shortName = utf8_encode(trim(substr($row, 107, 27)));
                $bic = trim(substr($row, 139, 11));
                $cdc = trim(substr($row, 150, 2));

                try {
                    $stmt = $conn->prepare($upsertQuery);
                    $stmt->executeQuery([
                        $bankCode,
                        $bic,
                        $name,
                        $shortName,
                        $postalCode,
                        $locality,
                        $bic,
                        $name,
                        $shortName,
                        $postalCode,
                        $locality,
                    ]);
                } catch (Exception | DriverException $e) {
                    $problems[] = $e->getMessage();
                }
            }

        }

        if (count($problems) > 0) {
            foreach ($problems as $problem) {
                $io->error('Error: ' . $problem);
            }
            return Command::FAILURE;
        } else {
            $io->success('Bank codes imported');
            return Command::SUCCESS;
        }
    }
}
