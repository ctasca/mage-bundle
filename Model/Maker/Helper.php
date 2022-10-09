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
        // helper name question
        $question = $this->questionFactory->create('Enter Helper Name. It can be also a directory. (e.g. Data or Test/Data)');
        QuestionValidator::validatePath(
            $question,
            "Helper Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $helperPath = $this->questionHelper->ask($input, $output, $question);
        try {
            list($pathToHelper, $helperClassName, , $isOnlyClassName) = $this->extractPathParts($helperPath);
            if ($isOnlyClassName) {
                $helperPathArray = [$this->makeModulePathFromName($moduleName), 'Helper'];
            } else {
                $helperPathArray = [$this->makeModulePathFromName($moduleName), 'Helper', $pathToHelper];
            }
            $helperDirectoryPath = $this->makePathFromArray($helperPathArray);
            // create data provider
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setHelperNamespace($this->makeNamespace($helperDirectoryPath));
            $dataProvider->setClassName($helperClassName);

            $this->writeFileFromTemplateChoice(
                $helperDirectoryPath,
                $input,
                $output,
                self::HELPER_TEMPLATE_DIR,
                $dataProvider,
                $helperClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Helper created app/code/%s</info>',
                    $helperDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}