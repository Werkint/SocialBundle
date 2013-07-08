<?php
namespace Werkint\Bundle\SocialBundle\DependencyInjection\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * VkontakteFactory.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class VkontakteFactory extends AbstractFactory
{
    public function __construct()
    {
        $this->addOption('appid');
        $this->addOption('property', 'vkontakteId');
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'vkontakte';
    }

    protected function getListenerId()
    {
        return 'werkint.social.authlistener.vkontakte';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.vkontakte.' . $id;
        $provider = new DefinitionDecorator('werkint.social.authprovider.vkontakte');
        $container->setDefinition($providerId, $provider);

        $provider
            ->addArgument(new Reference($userProviderId))
            ->addArgument($config['appid'])
            ->addArgument($config['property']);

        return $providerId;
    }
}