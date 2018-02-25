<?php

namespace App\Command;

use function PHPSTORM_META\elementType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class KoelInitDatabaseCommand extends Command
{
    protected static $defaultName = 'koel:init:database';

    protected function configure()
    {
        $this->setDescription('Setup the Database')
            ->addArgument('databaseURL', InputArgument::OPTIONAL, 'The database URL', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $databaseURL = $input->getArgument('databaseURL');

        if ($databaseURL === '') {
            $databaseURL = $this->askDatabaseCredentials($io);
        }

        //Create DB
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = array(
            'command' => 'doctrine:database:create --no-interaction --quiet',
        );

        $databaseCreateCommand = new ArrayInput($arguments);
        $returnCode = $command->run($databaseCreateCommand, $output);

        //Create Schema
        $command = $this->getApplication()->find('doctrine:schema:create');
        $arguments = array(
            'command' => 'doctrine:schema:create',
        );

        $databaseMigrateCommand = new ArrayInput($arguments);
        $returnCode = $command->run($databaseMigrateCommand, $output);

        $io->success('Databasesetup done');
    }

    public function askDatabaseCredentials(SymfonyStyle $io)
    {
        $dbtype = $io->choice('Which Database Type?', ['mysql', 'postgresql', 'sqlite'], 'sqlite');
        $host = $io->ask('Database Host?', '127.0.0.1');
        $port = $io->ask('Database Port?', null);
        $name = $io->ask('Database Name?', 'koel');
        $user = $io->ask('Database User?', 'root');
        $pass = $io->askHidden('Database Password?');

        $url = $dbtype . '://' . $user;

        if ($pass !== null) {
            var_dump($pass);
            $url .= ':' . $pass;
        }

        $url .= '@' . $host;

        if ($port !== null) {
            $url .= ':' . $port;
        }

        $url .= '/' . $name;

        return $url;
    }
}
