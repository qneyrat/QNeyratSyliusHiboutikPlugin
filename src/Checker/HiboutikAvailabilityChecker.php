<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Checker;

use QNeyrat\SyliusHiboutikPlugin\Provider\StockOfHiboutikProductProvider;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Inventory\Model\StockableInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;

class HiboutikAvailabilityChecker implements AvailabilityCheckerInterface
{
    /**
     * @var AvailabilityCheckerInterface
     */
    private AvailabilityCheckerInterface $parent;

    /**
     * @var StockOfHiboutikProductProvider
     */
    private StockOfHiboutikProductProvider $stockOfHiboutikProductProvider;

    /**
     * @var HiboutikProductChecker
     */
    private HiboutikProductChecker $hiboutikProductChecker;

    public function __construct(
        AvailabilityCheckerInterface $parent,
        HiboutikProductChecker $hiboutikProductChecker,
        StockOfHiboutikProductProvider $stockOfHiboutikProductProvider
    ) {
        $this->parent = $parent;
        $this->hiboutikProductChecker = $hiboutikProductChecker;
        $this->stockOfHiboutikProductProvider = $stockOfHiboutikProductProvider;
    }

    public function isStockAvailable(StockableInterface $stockable): bool
    {
        return $this->isStockSufficient($stockable , 1);
    }

    public function isStockSufficient(StockableInterface $stockable, int $quantity): bool
    {
        if ($stockable instanceof CodeAwareInterface && $this->hiboutikProductChecker->isHiboutikProduct($stockable)) {
            return $this->parent->isStockSufficient($stockable, $quantity) &&
                $quantity <= $this->stockOfHiboutikProductProvider->getStock($stockable);
        }

        return $this->parent->isStockSufficient($stockable, $quantity);
    }
}
