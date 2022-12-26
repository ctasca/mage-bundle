{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class {{class_name}} extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('');
        $this->setDescription('');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln("Completed");
    }
}