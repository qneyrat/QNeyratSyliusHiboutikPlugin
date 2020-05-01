<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Transformer;

const PRODUCT_CODE_SCHEME = "HIBOUTIK_%d";

class ProductCodeTransformer
{
    public function transform(int $hiboutikProductId): string
    {
        return sprintf(PRODUCT_CODE_SCHEME, $hiboutikProductId);
    }

    public function reverse(string $productCode): int
    {
        list($hiboutikProductHiboutik) = sscanf($productCode, PRODUCT_CODE_SCHEME);
        return $hiboutikProductHiboutik;
    }
}
