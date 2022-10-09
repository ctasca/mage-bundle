<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerHelperInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Helper extends AbstractMaker implements MakerHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create('Enter Helper class name. It can be also a directory. (e.g. Data or Test/Data)');
        QuestionValidator::validatePath(
            $question,
            "Helper class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $helperPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeCommonDataClassByPath(
                $helperPath,
                $moduleName,
                'Helper',
                self::HELPER_TEMPLATES_DIR,
                $input,
                $output,
                "Helper created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}