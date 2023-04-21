<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerUiComponentInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UiComponent extends AbstractMaker implements MakerUiComponentInterface
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
            list($templateLocator,)  = $this->locateTemplateDirectory(self::UI_COMPONENT_TEMPLATES_DIR);
            $question = $this->makeWebAreaChoicesQuestion(
                $templateLocator,
                'Please choose the area for Ui component'
            );
            $webArea = $this->questionHelper->ask($input, $output, $question);
            $question = $this->makeJsFilenameQuestion($webArea);
            $uiComponentPathFilename = $this->questionHelper->ask($input, $output, $question);
            list($pathToFile, $filename, , $isOnlyFilename) = $this->extractPathParts($uiComponentPathFilename);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $pathArray = $this->makeJsPathArray($moduleName, $webArea, $isOnlyFilename, $pathToFile);
            $jsDirectoryPath = $this->makePathFromArray($pathArray);
            $this->writeFileFromTemplateChoice(
                $jsDirectoryPath,
                $input,
                $output,
                self::UI_COMPONENT_TEMPLATES_DIR,
                $dataProvider,
                $filename,
                '.js'
            );
            $output->writeln(
                sprintf(
                    '<info>Completed! JS file successfully created in app/code/%s</info>',
                    $jsDirectoryPath
                )
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
