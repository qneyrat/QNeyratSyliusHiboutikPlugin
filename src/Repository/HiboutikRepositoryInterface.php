<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Repository;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikStockAvailable;

interface HiboutikRepositoryInterface {

    /**
     * @return HiboutikProduct[]
     */
    public function findProducts();

    /**
     * @param int $hiboutikProductId
     * @return HiboutikProduct|bool
     */
    public function findProductById(int $hiboutikProductId);

    /**
     * @param int $hiboutikProductId
     * @return HiboutikStockAvailable[]
     */
    public function findStocksAvailableByProduct(int $hiboutikProductId);

    /**
     * @param int $hiboutikProductId
     * @param int $hiboutikProductVariantId
     * @return HiboutikStockAvailable
     */
    public function findStocksAvailableByProductVariantId(int $hiboutikProductId, int $hiboutikProductVariantId);
}
