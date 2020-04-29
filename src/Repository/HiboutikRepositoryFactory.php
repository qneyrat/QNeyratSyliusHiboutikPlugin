<?php

namespace QNeyrat\SyliusHiboutikPlugin\Repository;

use QNeyrat\SyliusHiboutikPlugin\Client\HiboutikClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoCacheExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HiboutikRepositoryFactory
{
    public function __invoke(HiboutikClientInterface $hiboutikClient)
    {
        $phpDocExtractor = new PhpDocExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            [],
            [$phpDocExtractor],
        );

        $objectNormalizer = new ObjectNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new PropertyInfoCacheExtractor($propertyInfo, new FilesystemAdapter()),
        );

        $serializer = new Serializer([
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            $objectNormalizer,
        ]);



        return new HiboutikRepository($hiboutikClient, $serializer);
    }
}
