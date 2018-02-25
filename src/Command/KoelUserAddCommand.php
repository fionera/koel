<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Egulias\EmailValidator\EmailValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class KoelUserAddCommand extends Command
{
    protected static $defaultName = 'koel:user:add';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * KoelUserAddCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a User to Koel')
            ->addArgument('mail', InputArgument::OPTIONAL, 'Mailaddress of the User', null)
            ->addArgument('name', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of the User', [])
            ->addOption('isAdmin', null, InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getArgument('mail') !== null) {
            $mail = $input->getArgument('mail');
        } else {
            $mail = $io->ask('Please enter the Mailaddress');
        }

        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $mail]) !== null) {
            $io->error('There is already a User with the Mailaddress: ' . $mail);
            return;
        }

        if ($input->getArgument('name') !== []) {
            $name = $input->getArgument('name');
        } else {
            $name = $io->ask('Please enter the Name');
        }

        $password = $io->askHidden('Please enter the password for the User');

        $isAdmin = $input->hasOption('isAdmin');

        $user = new User($name, $mail, '', $isAdmin);
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            $io->error('Error occurred: ' . $errorsString);
            return;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Created the User for ' . $name . '(' . $mail . ')');
    }
}
