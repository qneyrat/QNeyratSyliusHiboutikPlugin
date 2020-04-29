<?php

namespace QNeyrat\SyliusHiboutikPlugin\Repository;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikStockAvailable;

interface HiboutikRepositoryInterface {

    /**
     * @return HiboutikProduct[]
     */
    public function findProducts();

    /**
     * @param int $productId
     * @return HiboutikProduct|bool
     */
    public function findProductById(int $productId);

    /**
     * @param int $productId
     * @return HiboutikStockAvailable[]
     */
    public function findStocksAvailableByProduct(int $productId);
}
