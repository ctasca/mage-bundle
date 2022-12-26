<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerApiInterfaceInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiInterface extends AbstractMaker implements MakerApiInterfaceInterface
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
            list($templateLocator,)  = $this->locateTemplateDirectory(self::API_TEMPLATES_DIR);
            $question = $this->questionChoiceFactory->create(
                'Please choose the area for API interface',
                $templateLocator->getApiAreaChoices()
            );
            $question->setErrorMessage('Chosen area %s is invalid.');
            $area = $this->questionHelper->ask($input, $output, $question);
            $areaDirectory = $area;
            if (self::FUNCTIONAL_AREA_NAME === $area) {
                $areaDirectory = '';
            }
            if (!empty($areaDirectory)) {
                $pathArray = [$this->makeModulePathFromName($moduleName), 'Api', ucfirst($areaDirectory)];
            } else {
                $pathArray = [$this->makeModulePathFromName($moduleName), 'Api'];
            }
            $apiDirectoryPath = $this->makePathFromArray($pathArray);
            $question = $this->questionFactory->create('Enter Api interface name.');
            QuestionValidator::validateUcFirst(
                $question,
                "Api interface name is not valid.",
                self::MAX_QUESTION_ATTEMPTS
            );
            $apiInterfaceName = $this->questionHelper->ask($input, $output, $question);
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setNamespace($this->makeNamespace($apiDirectoryPath));
            $dataProvider->setInterfaceName($apiInterfaceName);
            $this->writeFileFromTemplateChoice(
                $apiDirectoryPath,
                $input,
                $output,
                self::API_TEMPLATES_DIR . DIRECTORY_SEPARATOR . $area,
                $dataProvider,
                $apiInterfaceName
            );

            $output->writeln(
                sprintf(
                    '<info>Completed! Api interface file successfully created in app/code/%s</info>',
                    $apiDirectoryPath
                )
            );
            $output->writeln('');

        } catch(\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}