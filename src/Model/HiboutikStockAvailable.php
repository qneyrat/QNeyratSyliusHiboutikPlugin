<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Model;

class HiboutikStockAvailable
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $productSize;

    /**
     * @var int
     */
    private $warehouseId;

    /**
     * @var int
     */
    private $stockAvailable;

    /**
     * @var int
     */
    private $inventoryAlert;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getProductSize(): int
    {
        return $this->productSize;
    }

    /**
     * @param int $productSize
     */
    public function setProductSize(int $productSize): void
    {
        $this->productSize = $productSize;
    }

    /**
     * @return int
     */
    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    /**
     * @param int $warehouseId
     */
    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    /**
     * @return int
     */
    public function getStockAvailable(): int
    {
        return $this->stockAvailable;
    }

    /**
     * @param int $stockAvailable
     */
    public function setStockAvailable(int $stockAvailable): void
    {
        $this->stockAvailable = $stockAvailable;
    }

    /**
     * @return int
     */
    public function getInventoryAlert(): int
    {
        return $this->inventoryAlert;
    }

    /**
     * @param int $inventoryAlert
     */
    public function setInventoryAlert(int $inventoryAlert): void
    {
        $this->inventoryAlert = $inventoryAlert;
    }
}
