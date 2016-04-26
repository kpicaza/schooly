<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\Grade\CreateGradeCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CreateGradeCommandTest.
 */
class CreateGradeCommandTest extends KernelTestCase
{
    const SUBJECT = 'Testing create grade command';
    const ERROR = 'Error occurred creating a Grade.';
    const QUESTION = 'Please enter the subject of the Grade: ';

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    protected function createApp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateGradeCommand());

        return $application;
    }

    public function testExecute()
    {
        $application = $this->createApp();

        $command = $application->find('grade:create');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
            'subject' => self::SUBJECT,
        ));

        $this->assertRegExp('/'.self::SUBJECT.'/', $commandTester->getDisplay());
    }

    public function testExecuteFail()
    {
        $application = $this->createApp();

        $command = $application->find('grade:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream('<script>document.location = "http://google.com"</script>?983·$%%&"·$&%%&'));

        $commandTester->execute(array(
            'command' => $command->getName(),
            // 'subject' => self::SUBJECT,
        ));

        $this->assertRegExp('/'.self::ERROR.'/', $commandTester->getDisplay());
    }

    public function testExecutePrompt()
    {
        $application = $this->createApp();

        $command = $application->find('grade:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(sprintf('%s', self::SUBJECT)));

        $commandTester->execute(array(
            'command' => $command->getName(),
            // 'subject' => self::SUBJECT,
        ));

        $this->assertRegExp('/'.self::SUBJECT.'/', $commandTester->getDisplay());
    }
}
