<?php
namespace Werkint\Bundle\WebappBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class Configuration implements ConfigurationInterface {

	private $alias;

	public function __construct($alias) {
		$this->alias = $alias;
	}

	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root($this->alias)->children();

		$rootNode
			->scalarNode('xdpath')->end();
		$rootNode
			->arrayNode('social')
			->isRequired()
			->requiresAtLeastOneElement()
			->useAttributeAsKey('name')
			->prototype('array')->children()
			->scalarNode('name')->end()
			->scalarNode('id')->isRequired()->end()
			->scalarNode('secret')->isRequired()->end()
			->end()->end()
			->end();

		$rootNode->end();
		return $treeBuilder;
	}
}