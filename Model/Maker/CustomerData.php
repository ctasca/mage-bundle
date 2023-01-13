<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerCustomerDataInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerData extends AbstractMaker implements MakerCustomerDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->makeModuleNameQuestion();
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create(
            'Enter CustomerData class name. It can be also a directory. (e.g. Data or Test/Data)'
        );
        QuestionValidator::validatePath(
            $question,
            "Customer Data Name is not valid.",
            self::MAX_QUESTION_ATTEMPTS
        );
        $customerDataPath = $this->questionHelper->ask($input, $output, $question);
        try {
            $this->writeClassFromTemplateChoice(
                $customerDataPath,
                $moduleName,
                'CustomerData',
                self::CUSTOMER_DATA_TEMPLATES_DIR,
                $input,
                $output,
                "Customer Data class created in app/code/%s"
            );
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
