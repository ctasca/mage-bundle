<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint


declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Util;

use Ctasca\MageBundle\Api\UtilCommandInterface;
use Ctasca\MageBundle\Console\Question\Choice\Factory as QuestionChoiceFactory;
use Ctasca\MageBundle\Console\Question\Factory as QuestionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Encryption\Encryptor as MagentoEncryptor;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Encryptor implements UtilCommandInterface
{
    private State $state;
    private MagentoEncryptor $encryptor;
    private QuestionChoiceFactory $questionChoiceFactory;
    private SymfonyQuestionHelper $questionHelper;
    private QuestionFactory $questionFactory;

    /**
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\Encryption\Encryptor $encryptor
     * @param \Ctasca\MageBundle\Console\Question\Choice\Factory $questionChoiceFactory
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     * @param \Ctasca\MageBundle\Console\Question\Factory $questionFactory
     */
    public function __construct(
        State $state,
        MagentoEncryptor $encryptor,
        QuestionChoiceFactory $questionChoiceFactory,
        SymfonyQuestionHelper $questionHelper,
        QuestionFactory $questionFactory
    ) {
        $this->state = $state;
        $this->encryptor = $encryptor;
        $this->questionChoiceFactory = $questionChoiceFactory;
        $this->questionHelper = $questionHelper;
        $this->questionFactory = $questionFactory;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        try {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
            $question = $this->questionChoiceFactory->create(
                'Choose whether you want to encrypt or decrypt',
                $this->getEncryptorChoices()
            );
            $question->setErrorMessage('Chosen action %s is invalid.');
            $action = $this->questionHelper->ask($input, $output, $question);

            $question = $this->questionFactory->create(sprintf('Enter string to %s', $action));
            $string = $this->questionHelper->ask($input, $output, $question);

            if ($action === 'encrypt') {
                $output->writeln(
                    '<info>' . $this->encryptor->encrypt($string) . '</info>'
                );
            }

            if ($action === 'decrypt') {
                $output->writeln(
                    '<info>' . $this->encryptor->decrypt($string) . '</info>'
                );
            }

            $output->writeln('');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * @return string[]
     */
    private function getEncryptorChoices(): array
    {
        return ['encrypt', 'decrypt'];
    }
}
