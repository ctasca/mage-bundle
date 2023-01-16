<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModelSetInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $question = $this->questionFactory->create(
            'Enter Model class name. It can be also a directory. (e.g. Test or Test/MyModel)'
        );
        QuestionValidator::validatePath(
            $question,
            "Model class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $modelPath = $this->questionHelper->ask($input, $output, $question);
        list($pathToModel, $modelClassName, , $isOnlyClassName) = $this->extractPathParts($modelPath);
        // ask if the model implements an interface
        $confirmationQuestion = $this->confirmationQuestionFactory->create(
            strintf('Does this model implement the API/Data %sInterface?', $modelClassName)
        );
        $isImplementingInterface = $this->questionHelper->ask($input, $output, $confirmationQuestion);
        $interfaceName = null;
        if ($isImplementingInterface === true) {
            $interfaceName = $modelClassName . 'Interface';
        }
        // main table question
        $question = $this->questionFactory->create('Enter main table name');
        QuestionValidator::validateRequired(
            $question,
            "Main table name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $mainTableName = $this->questionHelper->ask($input, $output, $question);
        // field name id question
        $question = $this->questionFactory->create('Enter field name id');
        QuestionValidator::validateRequired(
            $question,
            "Field name id is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $fieldNameId = $this->questionHelper->ask($input, $output, $question);
        try {
            if ($isOnlyClassName) {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model'];
                $useModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $modelClassName];
                $resourceModelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', 'ResourceModel'];
                $useResourceModelPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    'ResourceModel',
                    $modelClassName
                ];
                $collectionPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    'ResourceModel',
                    $modelClassName
                ];
            } else {
                $modelPathArray = [$this->makeModulePathFromName($moduleName), 'Model', $pathToModel];
                $useModelPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    $pathToModel,
                    $modelClassName
                ];
                $resourceModelPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    'ResourceModel',
                    $pathToModel
                ];
                $useResourceModelPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    'ResourceModel',
                    $pathToModel,
                    $modelClassName
                ];
                $collectionPathArray = [
                    $this->makeModulePathFromName($moduleName),
                    'Model',
                    'ResourceModel',
                    $pathToModel,
                    $modelClassName
                ];
            }
            $modelDirectoryPath = $this->makePathFromArray($modelPathArray);
            $resourceModelDirectoryPath = $this->makePathFromArray($resourceModelPathArray);
            $collectionDirectoryPath = $this->makePathFromArray($collectionPathArray);
            // create data provider
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModelNamespace($this->makeNamespace($modelDirectoryPath));
            $dataProvider->setClassName($modelClassName);
            $dataProvider->setResourceModelNamespace($this->makeNamespace($resourceModelDirectoryPath));
            $dataProvider->setMainTable($mainTableName);
            $dataProvider->setIdFieldName($fieldNameId);
            $dataProvider->setCollectionNamespace($this->makeNamespace($collectionDirectoryPath));
            $dataProvider->setUseModel($this->makeNamespace($useModelPathArray));
            $dataProvider->setUseResourceModel($this->makeNamespace($useResourceModelPathArray));
            if ($interfaceName !== null) {
                $dataProvider->setDataInterface($interfaceName);
            }
            $this->writeFileFromTemplateChoice(
                $modelDirectoryPath,
                $input,
                $output,
                self::MODEL_TEMPLATES_DIR,
                $dataProvider,
                $modelClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Model created in app/code/%s</info>',
                    $modelDirectoryPath
                )
            );

            $this->writeFileFromTemplateChoice(
                $resourceModelDirectoryPath,
                $input,
                $output,
                self::RESOURCE_MODEL_TEMPLATES_DIR,
                $dataProvider,
                $modelClassName
            );

            $output->writeln(
                sprintf(
                    '<info>Resource model created in app/code/%s</info>',
                    $resourceModelDirectoryPath
                )
            );

            $this->writeFileFromTemplateChoice(
                $collectionDirectoryPath,
                $input,
                $output,
                self::COLLECTION_MODEL_TEMPLATES_DIR,
                $dataProvider,
                'Collection'
            );

            $output->writeln(
                sprintf(
                    '<info>Collection class created in app/code/%s</info>',
                    $collectionDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
