<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Transformer;

const PRODUCT_VARIANT_CODE_SCHEME = "HIBOUTIK_%d_%d";


class ProductVariantCodeTransformer
{
    public function transform(int $hiboutikProductId, int $hiboutikProductVariantId): string
    {
        return sprintf(PRODUCT_VARIANT_CODE_SCHEME, $hiboutikProductId, $hiboutikProductVariantId);
    }

    public function reverse(string $productVariantCode): array
    {
        return sscanf($productVariantCode, PRODUCT_VARIANT_CODE_SCHEME);
    }
}
