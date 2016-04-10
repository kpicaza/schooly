<?php

namespace AppBundle\Command\Grade;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $subject = $input->getArgument('subject');
            if (!$subject) {
                $helper = $this->getHelper('question');

                $question = new Question('Please enter the subject of the Grade: ', null);
                $question->setValidator(function ($answer) {
                    return $this->validateSubject($answer);
                });
                $question->setMaxAttempts(1);

                $subject = $helper->ask($input, $output, $question);
            }

            if (null === $subject) {
                throw new RuntimeException('Error occurred creating a Grade.');
            }

            $handler = $this->getContainer()->get('app.api_grade_handler');

            $grade = $handler->post(array('subject' => $subject));

        } catch (RuntimeException $e) {
            $output->writeln('Error occurred creating a Grade.');

            return;
        }

        $output->writeln(sprintf('Grade named %s successfully created.', $grade->getSubject()));
    }

    protected function validateSubject($answer)
    {
        $validator = Validation::createValidator();
        $errors = $validator->validate($answer, array(new Assert\NotBlank(), new Assert\Regex("/^[A-Za-z0-9 _]*[A-Za-z]+[A-Za-z0-9 _]*$/")));
        if (count($errors)) {
            return null;
        }

        return $answer;
    }
}