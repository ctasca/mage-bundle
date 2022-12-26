<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerSchemaPatchInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class SchemaPatch extends AbstractMaker implements MakerSchemaPatchInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create('Enter Schema Patch class name. File is created in Setup/Patch/Schema directory.');
        QuestionValidator::validateUcFirst(
            $question,
            "Schema Patch class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $schemaPatchClassname = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeCommonDataClassByPath(
                $schemaPatchClassname,
                $moduleName,
                'Setup/Patch/Schema',
                self::SCHEMA_PATCH_TEMPLATES_DIR,
                $input,
                $output,
                "Setup Schema Patch created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}