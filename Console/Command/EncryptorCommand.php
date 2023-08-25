<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Model\Util\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EncryptorCommand extends Command
{
    private Encryptor $encryptor;

    /**
     * @param \Ctasca\MageBundle\Model\Util\Encryptor $encryptor
     */
    public function __construct(Encryptor $encryptor)
    {
        parent::__construct();
        $this->encryptor = $encryptor;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('magebundle:util:encryptor')
            ->setDescription('Encrypts/Decrypts a string using Magento crypt key');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->encryptor->execute($input, $output);
    }
}
