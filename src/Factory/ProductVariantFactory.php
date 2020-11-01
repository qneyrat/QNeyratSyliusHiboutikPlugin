<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Factory;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikSizeDetail;
use QNeyrat\SyliusHiboutikPlugin\Provider\PriceProvider;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductVariantFactory
{
    /**
     * @var FactoryInterface
     */
    private $productVariantFactory;

    /**
     * @var RepositoryInterface
     */
    private $channelPricingRepository;

    /**
     * @var FactoryInterface
     */
    private $channelPricingFactory;

    /**
     * @var PriceProvider
     */
    private $priceProvider;

    /**
     * ProductVariantFactory constructor.
     * @param FactoryInterface $productVariantFactory
     * @param RepositoryInterface $channelPricingRepository
     * @param FactoryInterface $channelPricingFactory
     * @param PriceProvider $priceProvider
     */
    public function __construct(
        FactoryInterface $productVariantFactory,
        RepositoryInterface $channelPricingRepository,
        FactoryInterface $channelPricingFactory,
        PriceProvider $priceProvider
    ) {
        $this->productVariantFactory = $productVariantFactory;
        $this->channelPricingRepository = $channelPricingRepository;
        $this->channelPricingFactory = $channelPricingFactory;
        $this->priceProvider = $priceProvider;
    }

    /**
     * @param string $productVariantCode
     * @param string $locale
     * @param array $channels
     * @param HiboutikProduct $hiboutikProduct
     * @param HiboutikSizeDetail $hiboutikSizeDetail
     * @return ProductVariantInterface
     */
    public function createProductVariant(
        string $productVariantCode,
        string $locale,
        array $channels,
        HiboutikProduct $hiboutikProduct,
        HiboutikSizeDetail $hiboutikSizeDetail
    ): ProductVariantInterface {

        /**
         * @var ProductVariantInterface $productVariant
         */
        $productVariant = $this->productVariantFactory->createNew();
        $productVariant->setCode($productVariantCode);
        $productVariant->setCurrentLocale($locale);
        $productVariant->setName(substr($hiboutikSizeDetail->getSizeName(), 0, 255)."-".$productVariantCode);

        foreach ($channels as $channel) {

            /**
             * @var ChannelPricingInterface|null $channelPricing
             */
            $channelPricing = $this->channelPricingRepository->findOneBy([
                'channelCode' => $channel->getCode(),
                'productVariant' => $productVariant,
            ]);

            if (null === $channelPricing) {

                /**
                 * @var ChannelPricingInterface $channelPricing
                 */
                $channelPricing = $this->channelPricingFactory->createNew();
                $channelPricing->setChannelCode($channel->getCode());
                $productVariant->addChannelPricing($channelPricing);
            }

            $productPrice = $this->priceProvider->getPrice($hiboutikProduct);
            $channelPricing->setPrice($productPrice);
            $channelPricing->setOriginalPrice($productPrice);
        }

        return $productVariant;
    }
}
