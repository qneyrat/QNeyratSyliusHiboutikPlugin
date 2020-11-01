<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Provider;

use QNeyrat\SyliusHiboutikPlugin\Exception\MalformedCodeForHiboutikProductException;
use QNeyrat\SyliusHiboutikPlugin\Repository\HiboutikRepositoryInterface;
use QNeyrat\SyliusHiboutikPlugin\Transformer\ProductVariantCodeTransformer;
use Sylius\Component\Resource\Model\CodeAwareInterface;

class StockOfHiboutikProductProvider
{
    /**
     * @var HiboutikRepositoryInterface
     */
    private $hiboutikRepository;

    /**
     * @var ProductVariantCodeTransformer
     */
    private $productVariantCodeTransformer;

    /**
     * StockOfHiboutikProductProvider constructor.
     * @param HiboutikRepositoryInterface $hiboutikRepository
     * @param ProductVariantCodeTransformer $productVariantCodeTransformer
     */
    public function __construct(
        HiboutikRepositoryInterface $hiboutikRepository,
        ProductVariantCodeTransformer $productVariantCodeTransformer
    ) {
        $this->hiboutikRepository = $hiboutikRepository;
        $this->productVariantCodeTransformer = $productVariantCodeTransformer;
    }

    /**
     * @param CodeAwareInterface $codeAware
     * @return int
     * @throws MalformedCodeForHiboutikProductException
     */
    public function getStock(CodeAwareInterface $codeAware): int
    {
        list($hiboutikProductId, $hiboutikProductVariantId) = $this
            ->productVariantCodeTransformer
            ->reverse($codeAware->getCode());

        $hiboutikStockAvailable = $this->hiboutikRepository
            ->findStocksAvailableByProductVariantId($hiboutikProductId, $hiboutikProductVariantId);

        return $hiboutikStockAvailable->getStockAvailable();
    }
}
