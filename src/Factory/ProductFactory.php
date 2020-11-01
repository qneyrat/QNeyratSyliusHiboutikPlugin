<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Factory;

use QNeyrat\SyliusHiboutikPlugin\Model\HiboutikProduct;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductTaxonRepositoryInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ProductFactory
{
    /**
     * @var ProductFactoryInterface
     */
    private $productFactory;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * @var ProductTaxonRepositoryInterface
     */
    private $productTaxonRepository;

    /**
     * @var FactoryInterface
     */
    private $productTaxonFactory;

    /**
     * ProductFactory constructor.
     * @param ProductFactoryInterface $productFactory
     * @param SlugGeneratorInterface $slugGenerator
     * @param ProductTaxonRepositoryInterface $productTaxonRepository
     * @param FactoryInterface $productTaxonFactory
     */
    public function __construct(
        ProductFactoryInterface $productFactory,
        SlugGeneratorInterface $slugGenerator,
        ProductTaxonRepositoryInterface $productTaxonRepository,
        FactoryInterface $productTaxonFactory
    ) {
        $this->productFactory = $productFactory;
        $this->slugGenerator = $slugGenerator;
        $this->productTaxonRepository = $productTaxonRepository;
        $this->productTaxonFactory = $productTaxonFactory;
    }

    /**
     * @param string $productCode
     * @param string $locale
     * @param array $channels
     * @param TaxonInterface $taxon
     * @param HiboutikProduct $hiboutikProduct
     * @return ProductInterface
     */
    public function createProduct(
        string $productCode,
        string $locale,
        array $channels,
        TaxonInterface $taxon,
        HiboutikProduct $hiboutikProduct
    ): ProductInterface {

        /**
         * @var ProductInterface $product
         */
        $product = $this->productFactory->createNew();
        $product->setCode($productCode);
        $this->addTaxonToProduct($product, $taxon);

        foreach ($channels as $channel) {
            $product->addChannel($channel);
        }

        $product->setMainTaxon($taxon);
        $product->setCurrentLocale($locale);
        $product->setFallbackLocale($locale);
        $product->setName(substr($hiboutikProduct->getProductModel(), 0, 255)."-".$productCode);
        $product->setEnabled(false);
        $product->setSlug($product->getSlug() ?: $this->slugGenerator->generate($product->getName()));

        return $product;
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

        /**
         * @var ProductTaxonInterface $productTaxon
         */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setTaxon($taxon);
        $product->addProductTaxon($productTaxon);
    }
}
