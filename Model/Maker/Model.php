<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModelInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Model extends AbstractMaker implements MakerModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        // model name question
        $question = $this->questionFactory->create('Enter Model Name. It can be also a directory. (e.g. Test or Test/MyModel)');
        QuestionValidator::validatePath(
            $question,
            "Model Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $modelPath = $this->questionHelper->ask($input, $output, $question);
        try {
            list($pathToModel, $modelClassName, , $isOnlyClassName) = $this->extractPathParts($modelPath);
            if ($isOnlyClassName) {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model'];
            } else {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $pathToModel];
            }
            $modelDirectoryPath = $this->makePathFromArray($modelPathArray);
            // create data provider
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModelNamespace($this->makeNamespace($modelDirectoryPath));
            $dataProvider->setClassName($modelClassName);

            $this->writeFileFromTemplateChoice(
                $modelDirectoryPath,
                $input,
                $output,
                self::MODEL_TEMPLATE_DIR,
                $dataProvider,
                $modelClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Model created app/code/%s</info>',
                    $modelDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}