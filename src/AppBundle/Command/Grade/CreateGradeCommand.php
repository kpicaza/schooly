<?php

namespace AppBundle\Command\Grade;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateGradeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('grade:create')
            ->setDescription('Create new Grade')
            ->addArgument(
                'subject',
                InputArgument::OPTIONAL,
                'Grade\'s Subject'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $subject = $input->getArgument('subject');
        if (!$subject) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter the name of the Grade: ', 'Grade Name');
            $subject = $helper->ask($input, $output, $question);
        }

        $repository = $this->getContainer()->get('app.grade_repository');

        try {
            $rawGrade = $repository->findNew($subject);

            $grade = $repository->insert($rawGrade);
        } catch (RuntimeException $e) {
            $output->writeln('Error occurred creating a Grade.');

            return;
        }

        $output->writeln(sprintf('Grade named %s successfully created.', $grade->getSubject()));
    }
}