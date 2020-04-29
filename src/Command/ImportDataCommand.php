<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Command;

use QNeyrat\SyliusHiboutikPlugin\Processor\HiboutikProductProcessor;
use QNeyrat\SyliusHiboutikPlugin\Repository\HiboutikRepositoryInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportDataCommand extends Command
{
    /**
     * @var HiboutikRepositoryInterface
     */
    private HiboutikRepositoryInterface $hiboutikRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var HiboutikProductProcessor
     */
    private HiboutikProductProcessor $hiboutikProductProcessor;

    /**
     * ImportDataCommand constructor.
     * @param HiboutikRepositoryInterface $hiboutikClient
     * @param ChannelRepositoryInterface $channelRepository
     * @param HiboutikProductProcessor $hiboutikProductProcessor
     */
    public function __construct(
        HiboutikRepositoryInterface $hiboutikClient,
        ChannelRepositoryInterface $channelRepository,
        HiboutikProductProcessor $hiboutikProductProcessor
    ) {
        parent::__construct();
        $this->hiboutikRepository = $hiboutikClient;
        $this->channelRepository = $channelRepository;
        $this->hiboutikProductProcessor = $hiboutikProductProcessor;
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $hiboutikProducts = $this->hiboutikRepository->findProducts();

        /**
         * @var ChannelInterface[] $channels
         */
        $channels = $this->channelRepository->findAll();
        foreach ($channels as $channel) {
            foreach ($hiboutikProducts as $hiboutikProduct) {
                $this->hiboutikProductProcessor->process(
                    $channel,
                    $hiboutikProduct,
                    $this->hiboutikRepository->findStocksAvailableByProduct($hiboutikProduct->getProductId())
                );
            }
        }
    }
}
