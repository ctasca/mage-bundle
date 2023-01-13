<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerViewModelInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewModel extends AbstractMaker implements MakerViewModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create(
            'Enter View Model class name. It can be also a directory. (e.g. Data or Test/Data)'
        );
        QuestionValidator::validatePath(
            $question,
            "View Model class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $viewModelPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $viewModelPath,
                $moduleName,
                'ViewModel',
                self::VIEW_MODEL_TEMPLATES_DIR,
                $input,
                $output,
                "View Model created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
