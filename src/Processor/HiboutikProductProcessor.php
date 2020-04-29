<?php

namespace QNeyrat\SyliusHiboutikPlugin\Processor;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikStockAvailable;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class HiboutikProductProcessor
{
    /**
     * @var ProductFactoryInterface
     */
    private ProductFactoryInterface $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var SlugGeneratorInterface
     */
    private SlugGeneratorInterface $slugGenerator;
    /**
     * @var LocaleProviderInterface
     */
    private LocaleProviderInterface $localeProvider;

    /**
     * HiboutikProductProcessor constructor.
     * @param ProductFactoryInterface $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param SlugGeneratorInterface $slugGenerator
     * @param LocaleProviderInterface $localeProvider
     */
    public function __construct(
        ProductFactoryInterface $productFactory,
        ProductRepositoryInterface $productRepository,
        SlugGeneratorInterface $slugGenerator,
        LocaleProviderInterface $localeProvider
//        ProductVariantRepositoryInterface $productVariantRepository,
//        ProductVariantFactoryInterface $productVariantFactory,
//        FactoryInterface $channelPricingFactory,
//        RepositoryInterface $channelPricingRepository
    ) {

        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->slugGenerator = $slugGenerator;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param ChannelInterface $channel
     * @param HiboutikProduct $hiboutikProduct
     * @param HiboutikStockAvailable[] $stockAvailables
     */
    public function process(ChannelInterface $channel, HiboutikProduct $hiboutikProduct, array $stockAvailables): void
    {
        $product = $this->getProduct($hiboutikProduct->getProductId());

        $product->setName($hiboutikProduct->getProductModel());
        $product->setSlug($product->getSlug() ?: $this->slugGenerator->generate($product->getName()));
//
//        $this->setVariants($product, $hiboutikProduct, $stockAvailables);
//        $this->setChannel($product);
//
        $this->productRepository->add($product);
    }

    /**
     * @param string $code
     * @return ProductInterface
     */
    private function getProduct(string $code): ProductInterface
    {
        /** @var ProductInterface|null $product */
        $product = $this->productRepository->findOneBy(['code' => $code]);
        if (null === $product) {
            /** @var ProductInterface $product */
            $product = $this->productFactory->createNew();
            $product->setCode($code);

            $locale = $this->localeProvider->getDefaultLocaleCode();
            $product->setCurrentLocale($locale);
            $product->setFallbackLocale($locale);

            $product->setEnabled(false);
        }

        return $product;
    }
//
//    /**
//     * @param string $code
//     * @return ProductVariantInterface
//     */
//    private function getProductVariant(string $code): ProductVariantInterface
//    {
//        /** @var ProductVariantInterface|null $productVariant */
//        $productVariant = $this->productVariantRepository->findOneBy(['code' => $code]);
//        if ($productVariant === null) {
//            /** @var ProductVariantInterface $productVariant */
//            $productVariant = $this->productVariantFactory->createNew();
//            $productVariant->setCode($code);
//        }
//
//        return $productVariant;
//    }
//
//    /**
//     * @param ProductInterface $product
//     * @param HiboutikProduct $hiboutikProduct
//     * @param HiboutikStockAvailable[] $stockAvailables
//     */
//    private function setVariants(ProductInterface $product, HiboutikProduct $hiboutikProduct, array $stockAvailables)
//    {
//        $hiboutikProductSizeDetails = $hiboutikProduct->getProductSizeDetails();
//        foreach ($hiboutikProductSizeDetails as $hiboutikProductSizeDetail) {
//            $productVariant = $this->getProductVariant(sprintf(
//                "%d-%d",
//                $hiboutikProduct->getProductId(),
//                $hiboutikProductSizeDetail->getSizeId()
//            ));
//
//            $productVariant->setName($hiboutikProductSizeDetail->getSizeName());
//
//            /** @var ChannelPricingInterface|null $channelPricing */
//            $channelPricing = $this->channelPricingRepository->findOneBy([
//                'channelCode' => $this->channel->getCode(),
//                'productVariant' => $productVariant,
//            ]);
//
//            if (null === $channelPricing) {
//                /** @var ChannelPricingInterface $channelPricing */
//                $channelPricing = $this->channelPricingFactory->createNew();
//                $channelPricing->setChannelCode($this->channel->getCode());
//                $productVariant->addChannelPricing($channelPricing);
//            }
//
//            $channelPricing->setPrice($hiboutikProduct->getProductPrice());
//            $channelPricing->setOriginalPrice($hiboutikProduct->getProductPrice());
//
//            foreach ($stockAvailables as $stockAvailable) {
//                if ($stockAvailable->getProductSize() === $hiboutikProductSizeDetail->getSizeId()) {
//                    $productVariant->setOnHand($stockAvailable->getStockAvailable());
//                }
//            }
//
//            $product->addVariant($productVariant);
//        }
//    }
//
//    private function setChannel(ProductInterface $product)
//    {
//        /** @var ChannelInterface|null $channel */
//        $channel = $this->channelRepository->findOneBy(['code' => $this->channelCode]);
//        if ($channel === null) {
//            return;
//        }
//        $product->addChannel($channel);
//    }
}
