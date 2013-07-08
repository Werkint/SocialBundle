<?php
namespace Werkint\Bundle\SocialBundle\Service\Provider;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Werkint\Bundle\SocialBundle\Service\Bridge\VkontakteBridge;

/**
 * VkontakteProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class VkontakteProvider implements
    AuthenticationProviderInterface
{
    protected $bridge;
    protected $provider;
    protected $appid;
    protected $property;

    public function __construct(
        VkontakteBridge $bridge,
        UserProviderInterface $provider,
        $appid,
        $property
    ) {
        $this->bridge = $bridge;
        $this->provider = $provider;
        $this->appid = $appid;
        $this->property = $property;
    }

    public function supportsClass($class)
    {
        return $this->provider->supportsClass($class);
    }

    public function findByVkontakte($userid)
    {
        $provider = $this->provider;
        /** @var EntityRepository $provider */
        return $provider->findOneBy([
            $this->property => $userid,
        ]);
    }

    public function loadUserByUsername($userid)
    {
        $user = $this->findByVkontakte($userid);
        $data = $this->bridge->getProfile($userid);

        if (empty($data)) {
            $user = null;
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on VKontakte');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $property = $this->property;
        $property = 'get' . strtoupper($property[0]) . substr($property, 1);
        if (!$this->supportsClass(get_class($user)) || !$user->$property()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->$property());
    }
}
