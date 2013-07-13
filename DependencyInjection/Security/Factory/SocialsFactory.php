<?php
namespace Werkint\Bundle\SocialBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SocialsFactory.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SocialsFactory extends AbstractFactory
{

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'socials';
    }

    protected function getListenerId()
    {
        return 'werkint.social.authentication.listener.vkontakte';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.vkontakte.' . $id;
        $provider = new DefinitionDecorator('werkint.social.authentication.provider.vkontakte');
        $container->setDefinition($providerId, $provider);

        $provider
            ->addArgument(new Reference($userProviderId))
            ->addArgument($config['appid'])
            ->addArgument('vkontakte');

        return $providerId;
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
        parent::addConfiguration($node);

        $builder = $node->children();

        $builder
            ->arrayNode('paths')
            ->isRequired()
            ->useAttributeAsKey('name')
            ->prototype('scalar')
            ->end()
            ->validate()
            ->ifTrue(function ($c) {
                $checkPaths = array();
                foreach ($c as $checkPath) {
                    if (in_array($checkPath, $checkPaths)) {
                        return true;
                    }

                    $checkPaths[] = $checkPath;
                }

                return false;
            })
            ->thenInvalid('Each resource owner should have a unique check_path.')
            ->end()
            ->end();
    }
}