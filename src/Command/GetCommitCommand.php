<?php

namespace App\Command;
use App\Controller\GithubController;
use Github\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;

class GetCommitCommand extends Command
{
    protected static $defaultName = 'app:get:commit';

    protected function configure()
    {
        $this
            ->setDescription('Showing last commit of')
            ->addArgument('owner/repository', InputArgument::REQUIRED, 'Pass owner and his repository')
            ->addArgument('branch', InputArgument::REQUIRED, 'Pass name of branch')
            ->addOption('service', null, InputOption::VALUE_OPTIONAL, 'Service (Github,Gitbucket etc.)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('owner/repository');
        $option = $input->getOption('service');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
            
            $data = explode('/',$arg1);
            
            if(count($data) != 2)
            {
                $io->error('The argument owner/repository is not valid');
                return 0;
            }

            $owner = $data[0];
            $repository = $data[1];
        }

        if ($option) {
            $service_name = $option;

            $io->note(sprintf('You passed an option: %s', $option));
        }
        else
        {
            $service_name = 'github';
        }

        if($service_name === 'github')
        {
            try
            {
                $service = new GithubController($owner,$repository);
            }
            catch(\Exception $e)
            {
                $io->error('The argument owner/repository is not valid');
                return 0;
            }

        }
        else
        {
            $io->error(sprintf('Not supported type of service : %s',$option));
            return 0;
        }
        $io->success(sprintf('Last commit SHA : %s', $service->getLastCommit()));

        return 0;
    }
}
