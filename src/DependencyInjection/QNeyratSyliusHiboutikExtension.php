<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class QNeyratSyliusHiboutikExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->getDefinition('qneyrat.hiboutik.client.hiboutik')
            ->replaceArgument(0, $config['account'])
            ->replaceArgument(1, $config['user'])
            ->replaceArgument(2, $config['api_key']);

        $container->getDefinition('qneyrat.hiboutik.provider.price')
            ->replaceArgument(0, $config['currency']);

        $container->getDefinition('qneyrat.hiboutik.provider.default_taxon')
            ->replaceArgument(0, $config['default_taxon']);

        $container->getDefinition('qneyrat.hiboutik.checker.hiboutik_product')
            ->replaceArgument(0, $config['product_code_prefix']);

        $container->getDefinition('qneyrat.hiboutik.transformer.product_code')
            ->replaceArgument(0, $config['product_code_prefix']);

        $container->getDefinition('qneyrat.hiboutik.transformer.variant_product_code')
            ->replaceArgument(0, $config['product_code_prefix']);
    }
}
