<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Processor;

use QNeyrat\SyliusHiboutikPlugin\Exception\MalformedCodeForHiboutikProductException;
use QNeyrat\SyliusHiboutikPlugin\Factory\ProductFactory;
use QNeyrat\SyliusHiboutikPlugin\Factory\ProductVariantFactory;
use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use QNeyrat\SyliusHiboutikPlugin\Provider\DefaultTaxonProvider;
use QNeyrat\SyliusHiboutikPlugin\Transformer\ProductCodeTransformer;
use QNeyrat\SyliusHiboutikPlugin\Transformer\ProductVariantCodeTransformer;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductProcessor
{
    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * @var RepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var ProductCodeTransformer
     */
    private $productCodeTransformer;

    /**
     * @var ProductVariantCodeTransformer
     */
    private $productVariantCodeTransformer;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var DefaultTaxonProvider
     */
    private $defaultTaxonProvider;

    /**
     * @var ProductVariantFactory
     */
    private $productVariantFactory;

    /**
     * ProductProcessor constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param RepositoryInterface $productVariantRepository
     * @param SlugGeneratorInterface $slugGenerator
     * @param LocaleProviderInterface $localeProvider
     * @param ProductCodeTransformer $productCodeTransformer
     * @param ProductVariantCodeTransformer $productVariantCodeTransformer
     * @param ProductFactory $productFactory
     * @param DefaultTaxonProvider $defaultTaxonProvider
     * @param ProductVariantFactory $productVariantFactory
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $productVariantRepository,
        SlugGeneratorInterface $slugGenerator,
        LocaleProviderInterface $localeProvider,
        ProductCodeTransformer $productCodeTransformer,
        ProductVariantCodeTransformer $productVariantCodeTransformer,
        ProductFactory $productFactory,
        DefaultTaxonProvider $defaultTaxonProvider,
        ProductVariantFactory $productVariantFactory
    ) {
        $this->productRepository = $productRepository;
        $this->slugGenerator = $slugGenerator;
        $this->channelRepository = $channelRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->localeProvider = $localeProvider;
        $this->productCodeTransformer = $productCodeTransformer;
        $this->productVariantCodeTransformer = $productVariantCodeTransformer;
        $this->productFactory = $productFactory;
        $this->defaultTaxonProvider = $defaultTaxonProvider;
        $this->productVariantFactory = $productVariantFactory;
    }

    /**
     * @param HiboutikProduct $hiboutikProduct
     * @param array $stockAvailables
     * @throws \Exception
     */
    public function process(HiboutikProduct $hiboutikProduct, array $stockAvailables): void
    {
        $locale = $this->localeProvider->getDefaultLocaleCode();
        $product = $this->getProduct($locale, $hiboutikProduct, $stockAvailables);
        $this->setStockOnProduct($product, $stockAvailables);
        $this->productRepository->add($product);
    }

    /**
     * @param string $locale
     * @param HiboutikProduct $hiboutikProduct
     * @param array $stockAvailables
     * @return array
     * @throws \Exception
     */
    private function createOrGetProducts(
        string $locale,
        HiboutikProduct $hiboutikProduct,
        array $stockAvailables
    ): array {
        $productCode = $this->productCodeTransformer->transform($hiboutikProduct->getProductId());

        /** @var ProductInterface|null $product */
        $product = $this->productRepository->findOneBy(['code' => $productCode]);
        if (null !== $product) {
            return $product;
        }

        /** @var ChannelInterface[] $channels */
        $channels = $this->channelRepository->findAll();
        $taxon = $this->defaultTaxonProvider->getTaxon($locale);
        $product = $this->productFactory->createProduct($productCode, $locale, $channels, $taxon, $hiboutikProduct);
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
                    $productVariant = $this->productVariantFactory->createProductVariant(
                        $productVariantCode,
                        $locale,
                        $channels,
                        $hiboutikProduct,
                        $hiboutikProductSizeDetail
                    );
                }

                $this-> setStockOnProductVariant($productVariant, $hiboutikProduct->getProductId(), $stockAvailables);
                $product->addVariant($productVariant);
            }
        }

        return $product;
    }

    /**
     * @param ProductInterface $product
     * @param array $stockAvailables
     * @throws MalformedCodeForHiboutikProductException
     */
    private function setStockOnProduct(ProductInterface $product, array $stockAvailables): void
    {
        $productVariants = $product->getVariants();
        $hiboutikProductId = $this->productCodeTransformer->reverse($product->getCode());
        foreach ($productVariants as $productVariant) {
            $this->setStockOnProductVariant($productVariant, $hiboutikProductId, $stockAvailables);
        }
    }

    /**
     * @param ProductVariantInterface $productVariant
     * @param int $hiboutikProductId
     * @param array $stockAvailables
     */
    private function setStockOnProductVariant(
        ProductVariantInterface $productVariant,
        int $hiboutikProductId,
        array $stockAvailables
    ): void {
        foreach ($stockAvailables as $stockAvailable) {
            $productVariantCode = $this->productVariantCodeTransformer
                ->transform($hiboutikProductId, $stockAvailable->getProductSize());

            if ($productVariantCode === $productVariant->getCode()) {
                $productVariant->setOnHand($stockAvailable->getStockAvailable());
            }
        }
    }
}
