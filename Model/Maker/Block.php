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
        // model name question
        $question = $this->questionFactory->create('Enter Block Name. It can be also a directory. (e.g. Test or Test/MyBlock)');
        QuestionValidator::validatePath(
            $question,
            "Block Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $blockPath = $this->questionHelper->ask($input, $output, $question);
        try {
            list($pathToBlock, $blockClassName, , $isOnlyClassName) = $this->extractPathParts($blockPath);
            if ($isOnlyClassName) {
                $blockPathArray = [$this->makeModulePathFromName($moduleName), 'Block'];
            } else {
                $blockPathArray = [$this->makeModulePathFromName($moduleName), 'Block', $pathToBlock];
            }
            $blockDirectoryPath = $this->makePathFromArray($blockPathArray);
            // create data provider
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setBlockNamespace($this->makeNamespace($blockDirectoryPath));
            $dataProvider->setClassName($blockClassName);

            $this->writeFileFromTemplateChoice(
                $blockDirectoryPath,
                $input,
                $output,
                self::BLOCK_TEMPLATES_DIR,
                $dataProvider,
                $blockClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Model created app/code/%s</info>',
                    $blockDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}