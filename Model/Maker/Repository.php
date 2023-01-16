<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerRepositoryInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Exception\ClassDoesNotImplementInterfaceException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Exception\FileDoesNotExistException;

class Repository extends AbstractMaker implements MakerRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        //ask for model filename
        $question = $this->questionFactory->create(
            'Enter Model class name. It can be also a directory. (e.g. Test or Test/MyModel)'
        );
        QuestionValidator::validatePath(
            $question,
            "Model class name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $modelPath = $this->questionHelper->ask($input, $output, $question);
        try {
            list($pathToModel, $modelClassName, , $isOnlyClassName) = $this->extractPathParts($modelPath);
            $modulePath = $this->makeModulePathFromName($moduleName);
            $modelPathArray = [$modulePath, 'Model'];
            if ($isOnlyClassName !== true) {
                $modelPathArray = [$modulePath, 'Model', $pathToModel];
            }
            $modelDirectoryPath = $this->makePathFromArray($modelPathArray);
            $modelLocator = $this->getAppCodeLocator($modelDirectoryPath);
            $modelDirectory = $modelLocator->locate();
            if (!$modelLocator->getIoFile()->fileExists(
                $modelDirectory . DIRECTORY_SEPARATOR. $modelClassName . '.php'
            )) {
                throw new FileDoesNotExistException(
                    sprintf("Specified Model %s does not exists in module %s", $modelClassName, $moduleName)
                );
            } else {
                $modelNamespace = $this->makeNamespace($modelPathArray);
                $interfacePathArray = [$modulePath, 'Api', 'Data'];
                $interfaceNamespace = $this->makeNamespace($interfacePathArray);
                $modelInterface = $interfaceNamespace . "\\$modelClassName" . 'Interface';
            }
            $apiPathArray = [$modulePath, 'Api'];
            $apiDataPathArray = [$modulePath, 'Api', 'Data'];
            $interfaceLocator = $this->getAppCodeLocator($this->makePathFromArray($apiDataPathArray));
            $interfaceDirectory = $interfaceLocator->locate();
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setNamespace($modelNamespace);
            $dataProvider->setApiNamespace($this->makeNamespace($apiPathArray));
            $dataProvider->setModelName($modelClassName);
            $dataProvider->setRepositoryName($modelClassName);
            $dataProvider->setRepositoryNameArgument(lcfirst($modelClassName));
            if (!$interfaceLocator->getIoFile()->fileExists(
                $interfaceDirectory . DIRECTORY_SEPARATOR . $modelClassName . 'Interface'
            )) {
                $createInterfaceConfirmationQuestion = $this->confirmationQuestionFactory->create(
                    sprintf(
                        "Interface $modelClassName%s does not exist. Do you want to create it?",
                        'Interface'
                    )
                );
                $doCreateInterface = $this->questionHelper->ask($input, $output, $createInterfaceConfirmationQuestion);
                if ($doCreateInterface === true) {
                    $this->writeFileFromTemplateChoice(
                        $this->makePathFromArray($apiDataPathArray),
                        $input,
                        $output,
                        self::REPOSITORY_DATA_INTERFACE_TEMPLATES_DIR,
                        $dataProvider,
                        $modelClassName . 'Interface'
                    );
                    $output->writeln("<success>$modelClassName" . "Interface successfully created</success>");
                    $output->writeln('');
                }
            }
            $model = new \ReflectionClass($modelNamespace . "\\$modelClassName");
            if ($model->implementsInterface($modelInterface) === false) {
                throw new ClassDoesNotImplementInterfaceException(
                    sprintf(
                        "Specified Model %s does not implement interface %s",
                        $modelClassName,
                        $modelClassName . 'Interface'
                    )
                );
            }
            $this->writeFileFromTemplateChoice(
                $this->makePathFromArray($apiPathArray),
                $input,
                $output,
                self::REPOSITORY_INTERFACE_TEMPLATES_DIR,
                $dataProvider,
                $modelClassName . 'RepositoryInterface'
            );
            $output->writeln("<success>$modelClassName" . "RepositoryInterface successfully created</success>");
            $output->writeln('');
            $this->writeFileFromTemplateChoice(
                $this->makePathFromArray($apiDataPathArray),
                $input,
                $output,
                self::REPOSITORY_SEARCH_RESULT_INTERFACE_TEMPLATES_DIR,
                $dataProvider,
                $modelClassName . 'SearchResultInterface'
            );
            $output->writeln("<success>$modelClassName" . "SearchResultInterface successfully created</success>");
            $output->writeln('');
            $this->writeFileFromTemplateChoice(
                $this->makePathFromArray($modelPathArray),
                $input,
                $output,
                self::REPOSITORY_MODEL_TEMPLATES_DIR,
                $dataProvider,
                $modelClassName . 'Repository'
            );
            $output->writeln("<success>$modelClassName" . "Repository successfully created</success>");
            $output->writeln('');
            $output->writeln('<success>Completed!</success>');
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
