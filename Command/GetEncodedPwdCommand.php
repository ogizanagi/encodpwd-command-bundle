<?php


namespace Ogi\EncodPwdCommandBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetEncodedPwdCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('ogi:pwd_encode')
            ->setDescription('Get encoded password following configured encoders.')
            ->addArgument('password', InputArgument::REQUIRED, 'Password to encode.', null)
            ->addOption('salt', null, InputOption::VALUE_OPTIONAL, 'User salt.', null)
            ->addOption('user-class', null, InputOption::VALUE_OPTIONAL, 'The user class for which we want to generate password.', 'Symfony\Component\Security\Core\User\User');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $password = $input->getArgument('password');
        $salt = $input->getOption('salt');
        $userClass = $input->getOption('user-class');
        $container = $this->getContainer();

        $factory = $container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($userClass);
        $password = $encoder->encodePassword($password, $salt);

        $output->writeln("Encoding password...");
        $output->writeln("<comment>Your encoded password: </comment><info>".$password."</info>");
    }

} 