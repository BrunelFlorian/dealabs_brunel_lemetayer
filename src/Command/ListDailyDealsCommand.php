<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\AlertService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ListDailyDealsCommand',
    description: 'Add a short description for your command',
)]
class ListDailyDealsCommand extends Command
{
    private $userRepository;
    private $alertService;

    public function __construct(UserRepository $userRepository, AlertService $alertService)
    {
        $this->userRepository = $userRepository;
        $this->alertService = $alertService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupérer les utilisateurs qui ont opté pour les notifications par e-mail quotidienne
        $users = $this->userRepository->findAll();

        // Traiter les alertes pour chaque utilisateur
        foreach ($users as $user) {
            $this->alertService->processUserAlerts($user);
        }

        $output->writeln('Alerts processed successfully.');

        return Command::SUCCESS;
    }
}
