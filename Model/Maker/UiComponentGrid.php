<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerUiComponentInterface;
use Ctasca\MageBundle\Api\MakerUiComponentXmlInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UiComponentGrid extends AbstractMaker implements MakerUiComponentXmlInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator,)  = $this->locateTemplateDirectory(self::UI_COMPONENT_TEMPLATES_DIR);
            $question = $this->makeGridNamespaceQuestion(
                $templateLocator,
                'Please type the namespace for grid Ui component'
            );
            $namespace = $this->questionHelper->ask($input, $output, $question);

            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();

            $pathToFile = "ui-component/$namespace";

            $pathArray = $this->makeUiComponentXmlPathArray($moduleName, "adminhtml", true, $pathToFile);

            $jsDirectoryPath = $this->makePathFromArray($pathArray);

            $dataProvider->setModule($moduleName);
            $dataProvider->setLowercaseModule(strtolower($moduleName));
            $dataProvider->setNamespace($namespace);

            $gridXmlFilename = "{$namespace}_grid";

            $this->writeFileFromTemplateChoice(
                $jsDirectoryPath,
                $input,
                $output,
                self::UI_COMPONENT_TEMPLATES_DIR,
                $dataProvider,
                $gridXmlFilename,
                '.xml'
            );
            $output->writeln(
                sprintf(
                    '<info>Completed! Xml file successfully created in app/code/%s</info>',
                    $jsDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }




}
