<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerLoggerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Logger extends AbstractMaker implements MakerLoggerInterface
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
        // Logger Handler class name question
        $question = $this->questionFactory->create(
            'Enter Logger Handler class name. It can be also a directory. (e.g. Handler or Dummy/Handler)'
        );
        QuestionValidator::validatePath(
            $question,
            "Logger Handler class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $handlerPath = $this->questionHelper->ask($input, $output, $question);
        // Logger Handler filename question
        $question = $this->questionFactory->create(
            'Enter log filename name. It can be also a directory. (e.g. my-logger.log or dummy/my-logger.log)' .
            "\n<comment>This should be relative to the path MAGENTO_ROOT/var/log directory</comment>"
        );
        QuestionValidator::validateLoggerFilename(
            $question,
            "Log filename is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $logFilename = $this->questionHelper->ask($input, $output, $question);
        // Logger class name question
        $question = $this->questionFactory->create(
            'Enter Logger class name. It can be also a directory. (e.g. Logger or Dummy/Logger)'
        );
        QuestionValidator::validatePath(
            $question,
            "Logger class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $loggerPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $handlerPath,
                $moduleName,
                'Logger',
                self::LOGGER_HANDLER_TEMPLATES_DIR,
                $input,
                $output,
                "Logger Handler successfully created in app/code/%s",
                ['setLogFilename' => $logFilename]
            );
            $this->writeClassFromTemplateChoice(
                $loggerPath,
                $moduleName,
                'Logger',
                self::LOGGER_LOGGER_TEMPLATES_DIR,
                $input,
                $output,
                "Logger successfully created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
