<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerJQueryWidgetInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JQueryWidget extends AbstractMaker implements MakerJQueryWidgetInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator,)  = $this->locateTemplateDirectory(self::JQUERY_WIDGET_TEMPLATES_DIR);
            $question = $this->makeWebAreaChoicesQuestion(
                $templateLocator,
                'Please choose the area for the widget'
            );
            $webArea = $this->questionHelper->ask($input, $output, $question);
            $question = $this->makeJsFilenameQuestion($webArea);
            $widgetPathFilename = $this->questionHelper->ask($input, $output, $question);
            list($pathToFile, $filename, , $isOnlyFilename) = $this->extractPathParts($widgetPathFilename);
            $question = $this->questionFactory->create(
                'Enter widget name (e.g. widget.name)'
            );
            QuestionValidator::validateJQueryWidgetName(
                $question,
                "JQuery Widget name is not valid.",
                self::MAX_QUESTION_ATTEMPTS
            );
            $widgetName = $this->questionHelper->ask($input, $output, $question);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setWidgetName($widgetName);
            $pathArray = $this->makeJsPathArray($moduleName, $webArea, $isOnlyFilename, $pathToFile);
            $jsDirectoryPath = $this->makePathFromArray($pathArray);
            $this->writeFileFromTemplateChoice(
                $jsDirectoryPath,
                $input,
                $output,
                self::JQUERY_WIDGET_TEMPLATES_DIR,
                $dataProvider,
                $filename,
                '.js'
            );
            $output->writeln(
                '<info>JS file successfully created</info>'
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
