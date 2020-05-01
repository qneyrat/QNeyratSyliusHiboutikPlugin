<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Processor;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikSizeDetail;
use QNeyrat\SyliusHiboutikPlugin\Transformer\ProductCodeTransformer;
use QNeyrat\SyliusHiboutikPlugin\Transformer\ProductVariantCodeTransformer;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductTaxonRepositoryInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

const DEFAULT_TAXON_CODE = "HIBOUTIK";
const DEFAULT_TAXON_NAME = "Hiboutik";

class ProductProcessor
{
    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $channelPricingRepository;

    /**
     * @var FactoryInterface
     */
    private FactoryInterface $channelPricingFactory;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ProductFactoryInterface
     */
    private ProductFactoryInterface $resourceProductFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var SlugGeneratorInterface
     */
    private SlugGeneratorInterface $slugGenerator;

    /**
     * @var FactoryInterface
     */
    private FactoryInterface $productVariantFactory;

    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $productVariantRepository;

    /**
     * @var LocaleProviderInterface
     */
    private LocaleProviderInterface $localeProvider;
    /**
     * @var TaxonRepositoryInterface
     */
    private TaxonRepositoryInterface $taxonRepository;
    /**
     * @var FactoryInterface
     */
    private FactoryInterface $taxonFactory;
    /**
     * @var ProductTaxonRepositoryInterface
     */
    private ProductTaxonRepositoryInterface $productTaxonRepository;
    /**
     * @var FactoryInterface
     */
    private FactoryInterface $productTaxonFactory;
    /**
     * @var ProductCodeTransformer
     */
    private ProductCodeTransformer $productCodeTransformer;
    /**
     * @var ProductVariantCodeTransformer
     */
    private ProductVariantCodeTransformer $productVariantCodeTransformer;

    /**
     * ProductProcessor constructor.
     * @param ProductFactoryInterface $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param FactoryInterface $productVariantFactory
     * @param FactoryInterface $channelPricingFactory
     * @param RepositoryInterface $productVariantRepository
     * @param RepositoryInterface $channelPricingRepository
     * @param SlugGeneratorInterface $slugGenerator
     * @param LocaleProviderInterface $localeProvider
     * @param TaxonRepositoryInterface $taxonRepository
     * @param FactoryInterface $taxonFactory
     * @param ProductTaxonRepositoryInterface $productTaxonRepository
     * @param FactoryInterface $productTaxonFactory
     */
    public function __construct(
        ProductFactoryInterface $productFactory,
        ProductRepositoryInterface $productRepository,
        ChannelRepositoryInterface $channelRepository,
        FactoryInterface $productVariantFactory,
        FactoryInterface $channelPricingFactory,
        RepositoryInterface $productVariantRepository,
        RepositoryInterface $channelPricingRepository,
        SlugGeneratorInterface $slugGenerator,
        LocaleProviderInterface $localeProvider,
        TaxonRepositoryInterface $taxonRepository,
        FactoryInterface $taxonFactory,
        ProductTaxonRepositoryInterface $productTaxonRepository,
        FactoryInterface $productTaxonFactory,
        ProductCodeTransformer $productCodeTransformer,
        ProductVariantCodeTransformer $productVariantCodeTransformer

    ) {
        $this->resourceProductFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->slugGenerator = $slugGenerator;
        $this->channelRepository = $channelRepository;
        $this->productVariantFactory = $productVariantFactory;
        $this->productVariantRepository = $productVariantRepository;
        $this->channelPricingFactory = $channelPricingFactory;
        $this->channelPricingRepository = $channelPricingRepository;
        $this->localeProvider = $localeProvider;
        $this->taxonRepository = $taxonRepository;
        $this->taxonFactory = $taxonFactory;
        $this->productTaxonRepository = $productTaxonRepository;
        $this->productTaxonFactory = $productTaxonFactory;
        $this->productCodeTransformer = $productCodeTransformer;
        $this->productVariantCodeTransformer = $productVariantCodeTransformer;
    }

    /**
     * @param HiboutikProduct $hiboutikProduct
     * @param array $stockAvailables
     * @throws \Exception
     */
    public function process(HiboutikProduct $hiboutikProduct, array $stockAvailables): void
    {
        $locale = $this->localeProvider->getDefaultLocaleCode();
        $productCode = $this->productCodeTransformer->transform($hiboutikProduct->getProductId());
        $product = $this->getProduct($productCode, $locale, $hiboutikProduct, $stockAvailables);
        $this->setStockOnProduct($product, $stockAvailables);
        $this->productRepository->add($product);
    }

    /**
     * @param string $productCode
     * @param string $locale
     * @param HiboutikProduct $hiboutikProduct
     * @param array $stockAvailables
     * @return ProductInterface
     * @throws \Exception
     */
    private function getProduct(string $productCode, string $locale, HiboutikProduct $hiboutikProduct, array $stockAvailables): ProductInterface
    {
        /** @var ProductInterface|null $product */
        $product = $this->productRepository->findOneBy(['code' => $productCode]);
        if (null !== $product) {
            return $product;
        }

        /** @var ChannelInterface[] $channels */
        $channels = $this->channelRepository->findAll();
        $product = $this->createProduct($productCode, $locale, $channels, $hiboutikProduct);
        $hiboutikProductSizeDetails = $hiboutikProduct->getProductSizeDetails();
        if ($hiboutikProductSizeDetails !== null) {
            foreach ($hiboutikProductSizeDetails as $hiboutikProductSizeDetail) {
                $productVariantCode = $this->productVariantCodeTransformer->transform(
                    $hiboutikProduct->getProductId(),
                    $hiboutikProductSizeDetail->getSizeId()
                );
                /** @var ProductVariantInterface|null $productVariant */
                $productVariant = $this->productVariantRepository->findOneBy(['code' => $productVariantCode]);
                if ($productVariant === null) {
                    $productVariant = $this->createProductVariant(
                        $productVariantCode,
                        $locale,
                        $channels,
                        $hiboutikProduct,
                        $hiboutikProductSizeDetail
                    );
                }

                $this->setStockOnProductVariant($productVariant, $productCode, $stockAvailables);
                $product->addVariant($productVariant);
            }
        }

        return $product;
    }

    /**
     * @param ProductInterface $product
     * @param array $stockAvailables
     */
    private function setStockOnProduct(ProductInterface $product, array $stockAvailables): void
    {
        $productVariants = $product->getVariants();
        foreach ($productVariants as $productVariant) {
            $this->setStockOnProductVariant($productVariant, $product->getCode(), $stockAvailables);
        }
    }

    /**
     * @param ProductVariantInterface $productVariant
     * @param string $productCode
     * @param array $stockAvailables
     */
    private function setStockOnProductVariant(
        ProductVariantInterface $productVariant,
        string $productCode,
        array $stockAvailables
    ): void
    {
        foreach ($stockAvailables as $stockAvailable) {
            if (sprintf(
                    "%s-%d", $productCode, $stockAvailable->getProductSize()
                ) === $productVariant->getCode()) {
                $productVariant->setOnHand($stockAvailable->getStockAvailable());
            }
        }
    }

    /**
     * @param string $productCode
     * @param string $locale
     * @param array $channels
     * @param HiboutikProduct $hiboutikProduct
     * @return ProductInterface
     * @throws \Exception
     */
    private function createProduct(
        string $productCode,
        string $locale,
        array $channels,
        HiboutikProduct $hiboutikProduct
    )
    {
        /** @var ProductInterface $product */
        $product = $this->resourceProductFactory->createNew();
        $product->setCode($productCode);

        foreach ($channels as $channel) {
            $product->addChannel($channel);
        }

        $taxon = $this->getDefaultTaxon($locale);
        $this->setMainTaxon($product, $taxon);

        $product->setCurrentLocale($locale);
        $product->setFallbackLocale($locale);

        $product->setName(substr($hiboutikProduct->getProductModel(), 0, 255));
        $product->setEnabled(false);
        $product->setSlug($product->getSlug() ?: $this->slugGenerator->generate($product->getName()));

        return $product;
    }

    /**
     * @param string $productVariantCode
     * @param string $locale
     * @param array $channels
     * @param HiboutikProduct $hiboutikProduct
     * @param HiboutikSizeDetail $hiboutikSizeDetail
     * @return ProductVariantInterface
     */
    private function createProductVariant(
        string $productVariantCode,
        string $locale,
        array $channels,
        HiboutikProduct $hiboutikProduct,
        HiboutikSizeDetail $hiboutikSizeDetail
    ): ProductVariantInterface
    {
        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->productVariantFactory->createNew();
        $productVariant->setCode($productVariantCode);
        $productVariant->setCurrentLocale($locale);
        $productVariant->setName(substr($hiboutikSizeDetail->getSizeName(), 0, 255));
        foreach ($channels as $channel) {
            /** @var ChannelPricingInterface|null $channelPricing */
            $channelPricing = $this->channelPricingRepository->findOneBy([
                'channelCode' => $channel->getCode(),
                'productVariant' => $productVariant,
            ]);

            if (null === $channelPricing) {
                /** @var ChannelPricingInterface $channelPricing */
                $channelPricing = $this->channelPricingFactory->createNew();
                $channelPricing->setChannelCode($channel->getCode());
                $productVariant->addChannelPricing($channelPricing);
            }

            $channelPricing->setPrice((int) $hiboutikProduct->getProductPrice());
            $channelPricing->setOriginalPrice((int) $hiboutikProduct->getProductPrice());
        }

        return $productVariant;
    }

    /**
     * @param ProductInterface $product
     * @param TaxonInterface $taxon
     */
    private function setMainTaxon(ProductInterface $product, TaxonInterface $taxon): void
    {
        $product->setMainTaxon($taxon);
        $this->addTaxonToProduct($product, $taxon);
    }

    /**
     * @param ProductInterface $product
     * @param TaxonInterface $taxon
     */
    private function addTaxonToProduct(ProductInterface $product, TaxonInterface $taxon): void
    {
        $productTaxon = $this->productTaxonRepository->findOneByProductCodeAndTaxonCode(
            $product->getCode(),
            $taxon->getCode()
        );

        if (null !== $productTaxon) {
            return;
        }

        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setTaxon($taxon);
        $product->addProductTaxon($productTaxon);
    }

    /**
     * @param string $locale
     * @return TaxonInterface
     * @throws \Exception
     */
    private function getDefaultTaxon(string $locale): TaxonInterface
    {
        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => DEFAULT_TAXON_CODE]);
        if ($taxon === null) {
            $taxon = $this->taxonFactory->createNew();
            $taxon->setCode(DEFAULT_TAXON_CODE);
            $taxon->setName(DEFAULT_TAXON_NAME);
            $taxon->setCreatedAt(new \DateTime());
            $taxon->setSlug($this->slugGenerator->generate(DEFAULT_TAXON_CODE));
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);

            $this->taxonRepository->add($taxon);
        }

        return $taxon;
    }
}
