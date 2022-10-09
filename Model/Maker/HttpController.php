<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerHttpControllerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HttpController extends AbstractMaker implements MakerHttpControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
        list($templateLocator,)  = $this->locateTemplateDirectory('');
        $question = $this->questionChoiceFactory->create(
            'Please choose the router for this controller',
            $templateLocator->getRouteChoices()
        );
        $question->setErrorMessage('Chosen router %s is invalid.');
        $router = $this->questionHelper->ask($input, $output, $question);
        $controllerTemplatesDirectory = self::STANDARD_CONTROLLER_TEMPLATES_DIR;
        if ($router === 'admin') {
            $controllerTemplatesDirectory = self::ADMINHTML_CONTROLLER_TEMPLATES_DIR;
        }
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create('Enter Controller Name (e.g. Test)');
        QuestionValidator::validateControllerName(
            $question,
            "Controller Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $controllerName = $this->questionHelper->ask($input, $output, $question);
        $pathArray = [$this->makeModulePathFromName($moduleName), 'Controller', $controllerName];
        if ($router === 'admin') {
            $pathArray = [$this->makeModulePathFromName($moduleName), 'Controller', 'Adminhtml', $controllerName];
        }
        $controllerDirectoryPath = $this->makePathFromArray($pathArray);
        $question = $this->questionFactory->create('Enter Action Name (e.g. Test)');
        QuestionValidator::validateUcFirst(
            $question,
            "Action Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $actionName = $this->questionHelper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModule($moduleName);
            $dataProvider->setNamespace($this->makeNamespace($controllerDirectoryPath));
            $dataProvider->setClassName($actionName);

            $this->writeFileFromTemplateChoice(
                $controllerDirectoryPath,
                $input,
                $output,
                $controllerTemplatesDirectory,
                $dataProvider,
                $actionName
            );

            $output->writeln(
                sprintf(
                    '<info>Completed! Controller action successfully created in app/code/%s</info>',
                    $controllerDirectoryPath
                )
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}