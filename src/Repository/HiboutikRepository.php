<?php

namespace QNeyrat\SyliusHiboutikPlugin\Repository;

use QNeyrat\SyliusHiboutikPlugin\Client\HiboutikClientInterface;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikStockAvailable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HiboutikRepository implements HiboutikRepositoryInterface {

    /**
     * @var HiboutikClientInterface
     */
    private HiboutikClientInterface $client;

    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * HiboutikRepository constructor.
     * @param HiboutikClientInterface $client
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(HiboutikClientInterface $client, DenormalizerInterface $denormalizer)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
    }

    public function findProducts()
    {
        return $this->denormalizer->denormalize($this->client->products(), sprintf('%s[]', HiboutikProduct::class));
    }

    public function findProductById(int $productId)
    {
        return $this->denormalizer->denormalize($this->client->productById($productId), HiboutikProduct::class);
    }

    public function findStocksAvailableByProduct(int $productId)
    {
        return $this->denormalizer->denormalize($this->client->stocksAvailableByProductId($productId), sprintf('%s[]', HiboutikStockAvailable::class));
    }
}
