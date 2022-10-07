<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerEtcXmlInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

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
            list($templateLocator,)  = $this->locateTemplateDirectory('etc');
            $question = new ChoiceQuestion(
                'Please choose the area for the xml template',
                $templateLocator->getAreaChoices()
            );
            $question->setErrorMessage('Chosen area %s is invalid.');
            $area = $this->questionHelper->ask($input, $output, $question);
            $areaDirectory = $area . DIRECTORY_SEPARATOR;
            if (self::BASE_AREA_NAME === $area) {
                $areaDirectory = '';
            }
            list($template, $xmlTemplate) = $this->getTemplateContentFromChoice($input, $output, 'etc' . DIRECTORY_SEPARATOR . $area);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setModule($moduleName);
            $dataProvider->setLowercaseModule(strtolower($moduleName));
            $this->setDataProviderCustomData($dataProvider, 'etc' . DIRECTORY_SEPARATOR . $areaDirectory . $template);
            $xml = $this->makeFile($dataProvider, $xmlTemplate);
            $pathArray = [$this->makeModulePathFromName($moduleName), 'etc', $areaDirectory];
            $etcDirectoryPath = $this->makePathFromArray($pathArray);
            $moduleLocator = $this->getAppCodeLocator($etcDirectoryPath);
            $xmlDirectory = $moduleLocator->locate();
            $this->writeFile($moduleLocator, $xmlDirectory, str_replace('.tpl', '', $template), $xml);
            $output->writeln(
                sprintf(
                    '<info>Completed! Xml file successfully created in app/code/%s</info>',
                    $etcDirectoryPath
                )
            );
            $output->writeln('');
        } catch(\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}