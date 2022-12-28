<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerEtcXmlInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EtcXml extends AbstractMaker implements MakerEtcXmlInterface
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
            list($templateLocator,)  = $this->locateTemplateDirectory(self::XML_TEMPLATES_DIR);
            $question = $this->questionChoiceFactory->create(
                'Please choose the area for the xml template',
                $templateLocator->getAreaChoices()
            );
            $question->setErrorMessage('Chosen area %s is invalid.');
            $area = $this->questionHelper->ask($input, $output, $question);
            $areaDirectory = $area . DIRECTORY_SEPARATOR;
            if (self::GLOBAL_AREA_NAME === $area) {
                $areaDirectory = '';
            }
            $pathArray = [$this->makeModulePathFromName($moduleName), self::XML_TEMPLATES_DIR, $areaDirectory];
            $etcDirectoryPath = $this->makePathFromArray($pathArray);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setModule($moduleName);
            $dataProvider->setLowercaseModule(strtolower($moduleName));
            // write xml file. Note: No need to pass the filename as it will be picked up from the template filename
            $this->writeFileFromTemplateChoice(
                $etcDirectoryPath,
                $input,
                $output,
                self::XML_TEMPLATES_DIR . DIRECTORY_SEPARATOR . $area,
                $dataProvider
            );
            $output->writeln(
                sprintf(
                    '<info>Completed! Xml file successfully created in app/code/%s</info>',
                    $etcDirectoryPath
                )
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
