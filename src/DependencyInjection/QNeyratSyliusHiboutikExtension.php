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

        $definition = $container->getDefinition('qneyrat.hiboutik.client.hiboutik');
        $definition->addArgument($config['account']);
        $definition->addArgument($config['user']);
        $definition->addArgument($config['api_key']);
    }
}
