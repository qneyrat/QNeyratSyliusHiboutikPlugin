<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Repository;

use QNeyrat\SyliusHiboutikPlugin\Client\HiboutikClientInterface;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikStockAvailable;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HiboutikRepository implements HiboutikRepositoryInterface {

    /**
     * @var HiboutikClientInterface
     */
    private $client;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

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

    /**
     * @return array|object|HiboutikProduct[]
     * @throws ExceptionInterface
     */
    public function findProducts()
    {
        return $this->denormalizer
            ->denormalize($this->client->products(), sprintf('%s[]', HiboutikProduct::class)
        );
    }

    /**
     * @param int $hiboutikProductId
     * @return array|bool|object|HiboutikProduct
     * @throws ExceptionInterface
     */
    public function findProductById(int $hiboutikProductId)
    {
        return $this->denormalizer
            ->denormalize($this->client->productById($hiboutikProductId), HiboutikProduct::class);
    }

    /**
     * @param int $hiboutikProductId
     * @return array|object|HiboutikStockAvailable[]
     * @throws ExceptionInterface
     */
    public function findStocksAvailableByProduct(int $hiboutikProductId)
    {
        return $this->denormalizer->denormalize(
            $this->client->stocksAvailableByProductId($hiboutikProductId),
            sprintf('%s[]', HiboutikStockAvailable::class)
        );
    }

    /**
     * @param int $hiboutikProductId
     * @param int $hiboutikProductVariantId
     * @return array|object|HiboutikStockAvailable
     * @throws ExceptionInterface
     */
    public function findStocksAvailableByProductVariantId(int $hiboutikProductId, int $hiboutikProductVariantId)
    {
        return $this->denormalizer->denormalize(
            $this->client->stocksAvailableByProductVariantId($hiboutikProductId, $hiboutikProductVariantId),
            HiboutikStockAvailable::class
        );
    }
}
