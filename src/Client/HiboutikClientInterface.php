<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Client;

interface HiboutikClientInterface {
    public function products();
    public function productById(int $hiboutikProductId);
    public function stocksAvailableByProductId(int $hiboutikProductId);
    public function stocksAvailableByProductVariantId(int $hiboutikProductId, int $hiboutikProductVariantId);
}
