<?php
namespace EcommPro\CustomCurrency\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Magento\Framework\Setup\SchemaSetupInterface;
use EcommPro\CustomCurrency\Setup\PriceDecimalFixer;


class PriceDecimalFixerCommand extends Command
{
    protected $productCreateService;

    public function __construct(
        SchemaSetupInterface $setup,
        PriceDecimalFixer $fixer

    ) {
        parent::__construct();
        $this->setup = $setup;
        $this->fixer = $fixer;
    }

    protected function configure()
    {
        $this
            ->setName('custom-currency:price-decimal-fixer')
            ->setDescription('CustomCurrency price decimal fix precision.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fixer->execute($this->setup);
    }
}