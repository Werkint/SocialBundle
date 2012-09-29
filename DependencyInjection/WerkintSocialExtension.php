<?php
namespace Werkint\Bundle\SocialBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class WerkintSocialExtension extends Extension {
	public function load(array $configs, ContainerBuilder $container) {
		$processor = new Processor();
		$config = $processor->processConfiguration(new Configuration($this->getAlias()), $configs);
		$container->setParameter($this->getAlias(), $config);
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');
	}

	public function getAlias() {
		return 'werkint_social';
	}
}
