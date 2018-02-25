<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class KoelInitCommand extends Command
{
    protected static $defaultName = 'koel:init';

    protected function configure()
    {
        $this
            ->setDescription('Setup Koel')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        //Create default User
        $command = $this->getApplication()->find('koel:init:database');
        $arguments = array(
            'command' => 'koel:init:database'
        );

        $initDatabaseCommand = new ArrayInput($arguments);
        $returnCode = $command->run($initDatabaseCommand, $output);



        //Create default User
        $command = $this->getApplication()->find('koel:user:add');
        $arguments = array(
            'command' => 'koel:user:add',
            '--isAdmin'  => true,
        );

        $createUserCommand = new ArrayInput($arguments);
        $returnCode = $command->run($createUserCommand, $output);


        //Cache?
        //Redis -> Account details
        //Elasticsearch

        $io->success('Koel is ready to use');
    }
}
