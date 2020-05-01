<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Model;

class HiboutikSizeDetail
{
    /**
     * @var int
     */
    private int $sizeId;

    /**
     * @var String
     */
    private string $sizeName;

    /**
     * @var String
     */
    private string $barcode;

    /**
     * @return int
     */
    public function getSizeId(): int
    {
        return $this->sizeId;
    }

    /**
     * @param int $sizeId
     */
    public function setSizeId(int $sizeId): void
    {
        $this->sizeId = $sizeId;
    }

    /**
     * @return String
     */
    public function getSizeName(): String
    {
        return $this->sizeName;
    }

    /**
     * @param String $sizeName
     */
    public function setSizeName(String $sizeName): void
    {
        $this->sizeName = $sizeName;
    }

    /**
     * @return String
     */
    public function getBarcode(): String
    {
        return $this->barcode;
    }

    /**
     * @param String $barcode
     */
    public function setBarcode(String $barcode): void
    {
        $this->barcode = $barcode;
    }

}
