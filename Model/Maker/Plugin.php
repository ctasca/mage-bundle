<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerPluginInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Plugin extends AbstractMaker implements MakerPluginInterface
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
            'Enter Plugin class name. It can be also a directory. (e.g. Data or Test/Data)'
        );
        QuestionValidator::validatePath(
            $question,
            "Plugin class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $pluginPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $pluginPath,
                $moduleName,
                'Plugin',
                self::PLUGIN_TEMPLATES_DIR,
                $input,
                $output,
                "Plugin successfully created"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
