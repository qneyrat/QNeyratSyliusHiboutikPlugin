<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Provider;

use Money\Currency;
use Money\Parser\DecimalMoneyParser;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;

class PriceProvider
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var DecimalMoneyParser
     */
    private $moneyParser;

    /**
     * PriceProvider constructor.
     * @param string $hiboutikCurrency
     * @param DecimalMoneyParser $moneyParser
     */
    public function __construct(string $hiboutikCurrency, DecimalMoneyParser $moneyParser)
    {
        $this->currency = new Currency($hiboutikCurrency);
        $this->moneyParser = $moneyParser;
    }

    /**
     * @param HiboutikProduct $hiboutikProduct
     * @return int
     */
    public function getPrice(HiboutikProduct $hiboutikProduct): int
    {
        return (int) $this->moneyParser
            ->parse((string) $hiboutikProduct->getProductPrice(), $this->currency)
            ->getAmount();
    }
}
