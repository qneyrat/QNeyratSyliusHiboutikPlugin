<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Transformer;

use QNeyrat\SyliusHiboutikPlugin\Exception\MalformedCodeForHiboutikProductException;

const PRODUCT_VARIANT_CODE_SCHEME = "_%d_%d";

class ProductVariantCodeTransformer
{
    /**
     * @var string
     */
    private string $productCodePrefix;

    /**
     * ProductVariantCodeTransformer constructor.
     * @param string $productCodePrefix
     */
    public function __construct(string $productCodePrefix)
    {
        $this->productCodePrefix = $productCodePrefix;
    }

    /**
     * @param int $hiboutikProductId
     * @param int $hiboutikProductVariantId
     * @return string
     */
    public function transform(int $hiboutikProductId, int $hiboutikProductVariantId): string
    {
        return sprintf(
            $this->productCodePrefix . PRODUCT_VARIANT_CODE_SCHEME,
            $hiboutikProductId,
            $hiboutikProductVariantId
        );
    }

    /**
     * @param string $productVariantCode
     * @return array
     * @throws MalformedCodeForHiboutikProductException
     */
    public function reverse(string $productVariantCode): array
    {
        $n = sscanf(
            $productVariantCode,
            $this->productCodePrefix . PRODUCT_CODE_SCHEME,
            $hiboutikProductId,
            $hiboutikProductVariantId
        );

        if ($n !== 1 || $hiboutikProductId === null || $hiboutikProductVariantId === null) {
            throw new MalformedCodeForHiboutikProductException();
        }

        return [$hiboutikProductId, $hiboutikProductVariantId];
    }
}
