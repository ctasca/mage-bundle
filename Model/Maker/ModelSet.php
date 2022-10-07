<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModelSetInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question as CommandQuestion;

class ModelSet extends AbstractMaker implements MakerModelSetInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        // model name question
        $question = new CommandQuestion('Enter Model Name. It can be also a directory. (e.g. Test or Test/MyModel)');
        QuestionValidator::validatePath(
            $question,
            "Model Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $modelPath = $this->questionHelper->ask($input, $output, $question);
        // main table question
        $question = new CommandQuestion('Enter main table name');
        QuestionValidator::validateRequired(
            $question,
            "Main table name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $mainTableName = $this->questionHelper->ask($input, $output, $question);
        // field name id question
        $question = new CommandQuestion('Enter field name id');
        QuestionValidator::validateRequired(
            $question,
            "Field name id is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $fieldNameId = $this->questionHelper->ask($input, $output, $question);
        try {
            list($pathToModel, $modelClassName, , $isOnlyClassName) = $this->extractPathParts($modelPath);
            if ($isOnlyClassName) {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model'];
                $useModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $modelClassName];
                $resourceModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel'];
                $useResourceModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel', $modelClassName];
                $collectionPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel', $modelClassName];
            } else {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $pathToModel];
                $useModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $pathToModel, $modelClassName];
                $resourceModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel', $pathToModel];
                $useResourceModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel', $pathToModel, $modelClassName];
                $collectionPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel', $pathToModel, $modelClassName];
            }
            $modelDirectoryPath = $this->makePathFromArray($modelPathArray);
            $resourceModelDirectoryPath = $this->makePathFromArray($resourceModelPathArray);
            $collectionDirectoryPath = $this->makePathFromArray($collectionPathArray);
            $modelNamespace = $this->makeNamespace($modelDirectoryPath);
            $resourceModelNamespace = $this->makeNamespace($resourceModelDirectoryPath);
            $collectionNamespace = $this->makeNamespace($collectionDirectoryPath);
            $useModelNamespace = $this->makeNamespace($useModelPathArray);
            $useResourceModelNamespace = $this->makeNamespace($useResourceModelPathArray);
            // create data provider
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModelNamespace($modelNamespace);
            $dataProvider->setClassName($modelClassName);
            $dataProvider->setResourceModelNamespace($resourceModelNamespace);
            $dataProvider->setMainTable($mainTableName);
            $dataProvider->setIdFieldName($fieldNameId);
            $dataProvider->setCollectionNamespace($collectionNamespace);
            $dataProvider->setUseModel($useModelNamespace);
            $dataProvider->setUseResourceModel($useResourceModelNamespace);

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

            $this->writeFileFromTemplateChoice(
                $resourceModelDirectoryPath,
                $input,
                $output,
                self::RESOURCE_MODEL_TEMPLATE_DIR,
                $dataProvider,
                $modelClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Resource model created app/code/%s</info>',
                    $resourceModelDirectoryPath
                )
            );

            $this->writeFileFromTemplateChoice(
                $collectionDirectoryPath,
                $input,
                $output,
                self::COLLECTION_MODEL_TEMPLATE_DIR,
                $dataProvider,
                'Collection'
            );

            $output->writeln(
                sprintf(
                    '<info>Collection class created app/code/%s</info>',
                    $collectionDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}