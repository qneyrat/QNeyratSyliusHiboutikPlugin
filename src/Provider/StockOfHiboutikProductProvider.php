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
    private HiboutikRepositoryInterface $hiboutikRepository;

    /**
     * @var ProductVariantCodeTransformer
     */
    private ProductVariantCodeTransformer $hiboutikProductVariantIdToProductVariantCodeTransformer;

    public function __construct(HiboutikRepositoryInterface $hiboutikRepository, ProductVariantCodeTransformer $hiboutikProductVariantIdToProductVariantCodeTransformer)
    {
        $this->hiboutikRepository = $hiboutikRepository;
        $this->hiboutikProductVariantIdToProductVariantCodeTransformer = $hiboutikProductVariantIdToProductVariantCodeTransformer;
    }

    public function getStock(CodeAwareInterface $codeAware): int
    {
        $ids = $this->hiboutikProductVariantIdToProductVariantCodeTransformer->reverse($codeAware->getCode());
        if(count($ids) < 2) {
            throw new MalformedCodeForHiboutikProductException();
        }

        $hiboutikStockAvailable = $this->hiboutikRepository->findStocksAvailableByProductVariantId($ids[0], $ids[1]);

        return $hiboutikStockAvailable->getStockAvailable();
    }
}
