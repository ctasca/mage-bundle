<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerHttpControllerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question as CommandQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;

class HttpController extends AbstractMaker implements MakerHttpControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->questionHelper;
        $question = $this->makeModuleNameQuestion();
        $moduleName = $helper->ask($input, $output, $question);
        $question = new CommandQuestion('Enter Controller Name (e.g. Test)');
        QuestionValidator::validateControllerName(
            $question,
            "Controller Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $controllerName = $helper->ask($input, $output, $question);
        /** @var \Ctasca\MageBundle\Model\App\Code\Locator $moduleLocator */
        $controllerDirectoryPath = str_replace(
            '_',
            DIRECTORY_SEPARATOR,
            $moduleName) .
            DIRECTORY_SEPARATOR .
            'Controller' .
            DIRECTORY_SEPARATOR .
            $controllerName;
        $moduleLocator = $this->appCodeLocatorFactory->create(
            ['dirname' => $controllerDirectoryPath]
        );
        $controllerDirectory = $moduleLocator->locate();
        $question = new CommandQuestion('Enter Action Name (e.g. Test)');
        QuestionValidator::validateUcFirst(
            $question,
            "Action Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $actionName = $helper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator, $controllerTemplateDirectory)  = $this->locateTemplateDirectory('http-controller');
            $question = new ChoiceQuestion(
                sprintf('Please choose the action template to use for the %s controller', $controllerName),
                $templateLocator->getTemplatesChoices()
            );
            $question->setErrorMessage('Chosen template %s is invalid.');
            $template = $helper->ask($input, $output, $question);
            $output->writeln('<info>You have selected: '. $template . '</info>');
            $templateLocator->setTemplateFilename($template);
            $controllerTemplate = $this->getTemplateContent($templateLocator, $controllerTemplateDirectory);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setNamespace($this->makeNamespace($controllerDirectoryPath));
            $dataProvider->setClassName($actionName);
            $this->setDataProviderCustomData($dataProvider, $template);
            $action = $this->makeFile($dataProvider, $controllerTemplate);
            $this->writeFile($moduleLocator, $controllerDirectory, $actionName . '.php', $action);
            $output->writeln(
                sprintf(
                    '<info>Completed! Controller action successfully created in app/code/%s</info>',
                    $controllerDirectoryPath
                )
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }
    }
}