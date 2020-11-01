<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Command;

use QNeyrat\SyliusHiboutikPlugin\Processor\ProductProcessor;
use QNeyrat\SyliusHiboutikPlugin\Repository\HiboutikRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportHiboutikProductsDataCommand extends Command
{
    /**
     * @var HiboutikRepositoryInterface
     */
    private $hiboutikRepository;

    /**
     * @var ProductProcessor
     */
    private $productProcessor;

    /**
     * ImportDataCommand constructor.
     * @param HiboutikRepositoryInterface $hiboutikClient
     * @param ProductProcessor $productProcessor
     */
    public function __construct(
        HiboutikRepositoryInterface $hiboutikClient,
        ProductProcessor $productProcessor
    )
    {
        parent::__construct();
        $this->hiboutikRepository = $hiboutikClient;
        $this->productProcessor = $productProcessor;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('hiboutik:import')
            ->setDescription('Import a file.');
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $hiboutikProducts = $this->hiboutikRepository->findProducts();
        foreach ($hiboutikProducts as $hiboutikProduct) {
            $this->productProcessor->process(
                $hiboutikProduct,
                $this->hiboutikRepository->findStocksAvailableByProduct($hiboutikProduct->getProductId())
            );
        }
    }
}
