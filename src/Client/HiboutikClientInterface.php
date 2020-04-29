<?php

namespace QNeyrat\SyliusHiboutikPlugin\Client;

interface HiboutikClientInterface {
    public function products();
    public function productById(int $productId);
    public function stocksAvailableByProductId(int $productId);
}
