<?php
namespace Werkint\Bundle\SocialBundle;

use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Werkint\Bundle\SocialBundle\DependencyInjection\Factory\VkontakteFactory;

/**
 * WerkintSocialBundle.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintSocialBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        /** @var SecurityExtension $extension */
        $extension->addSecurityListenerFactory(new VkontakteFactory());
    }
}
