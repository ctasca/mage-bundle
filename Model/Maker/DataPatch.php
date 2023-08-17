<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerDataPatchInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataPatch extends AbstractMaker implements MakerDataPatchInterface
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
            'Enter Data Patch class name. File is created in Setup/Patch/Data directory.'
        );
        QuestionValidator::validateUcFirst(
            $question,
            "Data Patch class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $dataPatchClassname = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $dataPatchClassname,
                $moduleName,
                'Setup/Patch/Data',
                self::DATA_PATCH_TEMPLATES_DIR,
                $input,
                $output,
                "Setup Data Patch successfully created"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
