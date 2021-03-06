<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

const DEFAULT_CURRENCY = 'EUR';
const DEFAULT_TAXON = 'Hiboutik';
const PRODUCT_CODE_PREFIX = 'HIB';

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('q_neyrat_sylius_hiboutik');
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('q_neyrat_sylius_hiboutik');
        }

        $rootNode
            ->children()
            ->scalarNode('account')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('currency')->defaultValue(DEFAULT_CURRENCY)->end()
            ->scalarNode('default_taxon')->defaultValue(DEFAULT_TAXON)->end()
            ->scalarNode('product_code_prefix')->defaultValue(PRODUCT_CODE_PREFIX)->end()
            ->end();

        return $treeBuilder;
    }
}
