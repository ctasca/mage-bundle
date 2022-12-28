<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerBlockInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Block extends AbstractMaker implements MakerBlockInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create(
            'Enter Block class name. It can be also a directory. (e.g. Test or Test/MyBlock)'
        );
        QuestionValidator::validatePath(
            $question,
            "Block class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $blockPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeCommonDataClassByPath(
                $blockPath,
                $moduleName,
                'Block',
                self::BLOCK_TEMPLATES_DIR,
                $input,
                $output,
                "Block created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
