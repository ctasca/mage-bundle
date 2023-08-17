<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerExceptionInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Exception extends AbstractMaker implements MakerExceptionInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create(
            'Enter Exception class name. It can be also a directory. (e.g. DummyException or Dummy/MyException)'
        );
        QuestionValidator::validatePath(
            $question,
            "Exception class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $blockPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $blockPath,
                $moduleName,
                'Exception',
                self::EXCEPTION_TEMPLATES_DIR,
                $input,
                $output,
                "Exception class successfully created"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
