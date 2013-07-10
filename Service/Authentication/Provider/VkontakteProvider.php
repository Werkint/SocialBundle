<?php
namespace Werkint\Bundle\SocialBundle\Service\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Werkint\Bundle\SocialBundle\Service\Authentication\SocialToken;
use Werkint\Bundle\SocialBundle\Service\Bridge\VkontakteBridge;

/**
 * VkontakteProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class VkontakteProvider implements
    AuthenticationProviderInterface
{
    protected $providerKey;
    protected $bridge;
    protected $provider;
    protected $appid;

    public function __construct(
        VkontakteBridge $bridge,
        UserProviderInterface $provider,
        $providerKey,
        $appid
    ) {
        $this->bridge = $bridge;
        $this->provider = $provider;
        $this->providerKey = $providerKey;
        $this->appid = $appid;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            $newToken = new SocialToken(
                $user->getRoles(),
                $this->providerKey
            );
            $newToken->setUser($user);
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        throw new AuthenticationException('The VKontakte user could not be retrieved from the session.');
    }

    public function supports(TokenInterface $token)
    {
        if (!($token instanceof SocialToken)) {
            return false;
        }
        /** @var SocialToken $token */
        return $this->providerKey === $token->getProviderKey();
    }

    protected function createAuthenticatedToken($uid, $accessToken = null)
    {
        $user = $this->provider->loadUserByUsername($uid);
        $token = new SocialToken($user->getRoles(), $this->providerKey);
        $token->setUser($user);

        return $token;
    }

}
