<?php
namespace Werkint\Bundle\SocialBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Configuration implements ConfigurationInterface
{

    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias)->children();

        $rootNode
            ->scalarNode('xdpath')->end()
            ->scalarNode('checkpath')->end();
        $rootNode
            ->arrayNode('facebook')->children()
            ->scalarNode('appid')->end()
            ->scalarNode('secret')->end()
            ->arrayNode('scope')->prototype('scalar')->end()
            ->end();
        $rootNode
            ->arrayNode('vkontakte')->children()
            ->scalarNode('appid')->end()
            ->scalarNode('secret')->end()
            ->arrayNode('scope')->prototype('scalar')->end()
            ->end();

        $rootNode->end();
        return $treeBuilder;
    }
}