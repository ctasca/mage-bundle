{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;

class {{class_name}} extends Command
{
    /**
    * @param State $state
    */
    public function __construct(
        private readonly State $state
    ) {
        parent::__construct();
    }

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
        $this->state->setAreaCode(Area::AREA_FRONTEND);
        $output->writeln("Completed");
    }
}
