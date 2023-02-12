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

    protected $componentType = "grid";

    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator,)  = $this->locateTemplateDirectory(self::UI_COMPONENT_XML_TEMPLATES_DIR);
            $question = $this->makeUiComponentNamespaceQuestion(
                $templateLocator,
                'Please type the namespace for the Ui component'
            );
            $namespace = $this->questionHelper->ask($input, $output, $question);

            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();

            $pathToFile = "ui-component/$namespace";

            $pathArray = $this->makeUiComponentXmlPathArray(
                $moduleName,
                "adminhtml",
                true,
                $pathToFile);

            $xmlDirectoryPath = $this->makePathFromArray($pathArray);
            $dataProvider->setNamespace($namespace);

            $gridXmlFilename = "{$namespace}_grid";

            $this->writeFileFromTemplateChoice(
                $xmlDirectoryPath,
                $input,
                $output,
                self::UI_COMPONENT_XML_TEMPLATES_DIR,
                $dataProvider,
                $gridXmlFilename,
                '.xml'
            );
            $output->writeln(
                sprintf(
                    '<info>Completed! Xml file successfully created in app/code/%s</info>',
                    $xmlDirectoryPath
                )
            );

            $this->showFollowingStepsTips($moduleName, $output, $namespace);

        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }

    /**
     * @param mixed $moduleName
     * @param OutputInterface $output
     * @param mixed $namespace
     */
    protected function showFollowingStepsTips(string $moduleName, OutputInterface $output, string $namespace): void
    {
        $vendor = explode("_", $moduleName)[0];
        $module = explode("_", $moduleName)[1];


        $output->writeln(
            sprintf('
<info>
Remember to add this on your di.xml:
    <virtualType name="GridDataProvider"
         type="Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider">
        <arguments>
            <argument name="collection" xsi:type="object"
                      shared="false">%1$s\%2$s\Model\ResourceModel\YourModel\Collection</argument>
            <argument name="filterPool" xsi:type="object"
                      shared="false">FilterPool</argument>
        </arguments>
    </virtualType>

        <virtualType name="FilterPool" type="Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool">
        <arguments>
            <argument name="appliers" xsi:type="array">
                <item name="regular" xsi:type="object">Magento\Framework\View\Element\UiComponent\DataProvider\RegularFilter</item>
                <item name="fulltext" xsi:type="object">Magento\Framework\View\Element\UiComponent\DataProvider\FulltextFilter</item>
            </argument>
        </arguments>
    </virtualType>


    <type name="%1$s\%2$s\Model\ResourceModel\YourModel\Grid\Collection">
        <arguments>
            <argument name="mainTable" xsi:type="string">%3$s_grid</argument>
            <argument name="eventPrefix" xsi:type="string">%3$s_grid_collection</argument>
            <argument name="eventObject" xsi:type="string">grid_collection</argument>
            <argument name="resourceModel" xsi:type="string">%1$s\%2$s\Model\ResourceModel\YourModel</argument>
        </arguments>
    </type>
</info>'
                , $vendor, $module, $namespace));
    }
}
