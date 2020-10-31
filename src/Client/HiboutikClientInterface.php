<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Client;

interface HiboutikClientInterface
{
    /**
     * @return mixed
     */
    public function products();

    /**
     * @param int $hiboutikProductId
     * @return mixed
     */
    public function productById(int $hiboutikProductId);

    /**
     * @param int $hiboutikProductId
     * @return mixed
     */
    public function stocksAvailableByProductId(int $hiboutikProductId);

    /**
     * @param int $hiboutikProductId
     * @param int $hiboutikProductVariantId
     * @return mixed
     */
    public function stocksAvailableByProductVariantId(int $hiboutikProductId, int $hiboutikProductVariantId);
}
