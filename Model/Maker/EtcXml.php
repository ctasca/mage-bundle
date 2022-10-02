<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerEtcXmlInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class EtcXml extends AbstractMaker implements MakerEtcXmlInterface
{

    public function make(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->questionHelper;
        $question = $this->makeModuleNameQuestion();
        $moduleName = $helper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator,)  = $this->locateTemplateDirectory('etc');
            $question = new ChoiceQuestion(
                'Please choose the area for the xml template',
                $templateLocator->getAreaChoices()
            );
            $question->setErrorMessage('Chosen area %s is invalid.');
            $area = $helper->ask($input, $output, $question);
            $areaDirectory = $area . DIRECTORY_SEPARATOR;
            if ('base' === $area) {
                $areaDirectory = '';
            }
            list($templateLocator, $xmlTemplateDirectory)  = $this->locateTemplateDirectory('etc' . DIRECTORY_SEPARATOR . $area);
            $question = new ChoiceQuestion(
                sprintf('Please choose the xml template to use for the %s area', $area),
                $templateLocator->getTemplatesChoices()
            );
            $question->setErrorMessage('Chosen template %s is invalid.');
            $template = $helper->ask($input, $output, $question);
            $output->writeln('<info>You have selected: '. $template . '</info>');
            $templateLocator->setTemplateFilename($template);
            $xmlTemplate = $this->getTemplateContent($templateLocator, $xmlTemplateDirectory);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setModule($moduleName);
            $dataProvider->setLowercaseModule(strtolower($moduleName));
            $this->setDataProviderCustomData($dataProvider, 'etc' . DIRECTORY_SEPARATOR . $areaDirectory . $template);
            $xml = $this->makeFile($dataProvider, $xmlTemplate);
            $etcDirectoryPath = str_replace(
                    '_',
                    DIRECTORY_SEPARATOR,
                    $moduleName) .
                DIRECTORY_SEPARATOR .
                'etc' .
                DIRECTORY_SEPARATOR .
                $areaDirectory;
            $moduleLocator = $this->appCodeLocatorFactory->create(
                ['dirname' => $etcDirectoryPath]
            );
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
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }
    }
}