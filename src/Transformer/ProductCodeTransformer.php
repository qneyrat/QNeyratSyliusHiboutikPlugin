<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Transformer;

use QNeyrat\SyliusHiboutikPlugin\Exception\MalformedCodeForHiboutikProductException;

const PRODUCT_CODE_SCHEME = "_%d";

class ProductCodeTransformer
{
    /**
     * @var string
     */
    private $productCodePrefix;

    /**
     * ProductCodeTransformer constructor.
     * @param string $productCodePrefix
     */
    public function __construct(string $productCodePrefix)
    {
        $this->productCodePrefix = $productCodePrefix;
    }

    /**
     * @param int $hiboutikProductId
     * @return string
     */
    public function transform(int $hiboutikProductId): string
    {
        return sprintf($this->productCodePrefix . PRODUCT_CODE_SCHEME, $hiboutikProductId);
    }

    /**
     * @param string $productCode
     * @return int
     * @throws MalformedCodeForHiboutikProductException
     */
    public function reverse(string $productCode): int
    {
        $n = sscanf($productCode, $this->productCodePrefix . PRODUCT_CODE_SCHEME, $hiboutikProductId);
        if ($n !== 1 || $hiboutikProductId === null) {
            throw new MalformedCodeForHiboutikProductException();
        }

        return $hiboutikProductId;
    }
}
