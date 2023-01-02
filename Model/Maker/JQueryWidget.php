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
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
        list($templateLocator,)  = $this->locateTemplateDirectory(self::JQUERY_WIDGET_TEMPLATES_DIR);
        $question = $this->questionChoiceFactory->create(
            'Please choose the area for the widget',
            $templateLocator->getWebAreaChoices()
        );
        $question->setErrorMessage('Chosen area %s is invalid.');
        $webArea = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create(
            'Enter widget file name (without .js file extension).' .
            "\n\t<comment>It can also be a directory. E.g. (my-widget) or widgets/my-widget." .
            "\n\t" . sprintf("File will be created in the Company/Module/view/%s/web/js directory", $webArea) .
            "</comment>"
        );
        QuestionValidator::validateJsFilenamePath(
            $question,
            "JQuery Widget filename is not valid. Only lowercase characters, underscores or dashes.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $widgetPathFilename = $this->questionHelper->ask($input, $output, $question);
        list(, $filename, ,) = $this->extractPathParts($widgetPathFilename);
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
        $pathArray = [$this->makeModulePathFromName($moduleName), 'view', $webArea, 'web', 'js'];
        $jsDirectoryPath = $this->makePathFromArray($pathArray);
        try {
            $this->writeFileFromTemplateChoice(
                $jsDirectoryPath,
                $input,
                $output,
                self::JQUERY_WIDGET_TEMPLATES_DIR,
                $dataProvider,
                $filename,
                '.js'
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
