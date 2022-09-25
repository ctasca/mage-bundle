<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerHttpGetControllerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question as CommandQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;

class HttpGetController extends AbstractMaker implements MakerHttpGetControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->questionHelper;
        $question = new CommandQuestion('Enter Module Name (e.g. Company_Module)');
        QuestionValidator::validate(
            $question,
            "Module Name is required",
            self::MAX_QUESTION_ATTEMPTS
        );
        $moduleName = $helper->ask($input, $output, $question);
        $question = new CommandQuestion('Enter Controller Name (e.g. Test)');
        QuestionValidator::validate(
            $question,
            "Controller Name is required",
            self::MAX_QUESTION_ATTEMPTS
        );
        $controllerName = $helper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            $templateLocator = $this->templateLocatorFactory->create(['dirname' => 'http-get-controller']);
            $question = new ChoiceQuestion(
                sprintf('Please choose the template to use for the %s action', $controllerName),
                $templateLocator->getTemplatesChoices()
            );
            $question->setErrorMessage('Chosen template %s is invalid.');
            $template = $helper->ask($input, $output, $question);
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }

    }
}