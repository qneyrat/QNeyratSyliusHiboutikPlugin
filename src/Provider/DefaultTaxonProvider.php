<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Provider;

use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

class DefaultTaxonProvider
{
    /**
     * @var string
     */
    private string $defaultTaxonName;

    /**
     * @var TaxonRepositoryInterface
     */
    private TaxonRepositoryInterface $taxonRepository;

    /**
     * @var FactoryInterface
     */
    private FactoryInterface $taxonFactory;

    /**
     * @var SlugGeneratorInterface
     */
    private SlugGeneratorInterface $slugGenerator;

    /**
     * DefaultTaxonProvider constructor.
     * @param string $defaultTaxonName
     * @param TaxonRepositoryInterface $taxonRepository
     * @param FactoryInterface $taxonFactory
     * @param SlugGeneratorInterface $slugGenerator
     */
    public function __construct(
        string $defaultTaxonName,
        TaxonRepositoryInterface $taxonRepository,
        FactoryInterface $taxonFactory,
        SlugGeneratorInterface $slugGenerator
    ) {
        $this->defaultTaxonName = $defaultTaxonName;
        $this->taxonRepository = $taxonRepository;
        $this->taxonFactory = $taxonFactory;
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param string $locale
     * @return TaxonInterface
     * @throws \Exception
     */
    public function getTaxon(string $locale): TaxonInterface
    {
        $code = strtoupper($this->defaultTaxonName);
        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $code]);
        if ($taxon === null) {
            $taxon = $this->taxonFactory->createNew();
            $taxon->setCode($code);
            $taxon->setName($this->defaultTaxonName);
            $taxon->setCreatedAt(new \DateTime());
            $taxon->setSlug($this->slugGenerator->generate($code));
            $taxon->setCurrentLocale($locale);
            $taxon->setFallbackLocale($locale);

            $this->taxonRepository->add($taxon);
        }

        return $taxon;
    }
}
