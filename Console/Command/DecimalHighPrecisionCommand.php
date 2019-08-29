<?php
namespace EcommPro\CustomCurrency\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Magento\Framework\Setup\SchemaSetupInterface;
use EcommPro\CustomCurrency\Setup\DecimalHighPrecision;


class DecimalHighPrecisionCommand extends Command
{
    public function __construct(
        SchemaSetupInterface $setup,
        DecimalHighPrecision $fixer

    ) {
        parent::__construct();
        $this->setup = $setup;
        $this->fixer = $fixer;
    }

    protected function configure()
    {
        $this
            ->setName('custom-currency:decimal-high-precision')
            ->setDescription('CustomCurrency decimal hight precision. [CAUTION: USE AT YOUR OWN RISK]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fixer->execute($this->setup);
    }
}