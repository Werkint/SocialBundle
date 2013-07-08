<?php
namespace Werkint\Bundle\SocialBundle\Service\AuthProvider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Werkint\Bundle\SocialBundle\Service\Bridge\VkontakteBridge;
use Werkint\Bundle\SocialBundle\Service\SocialToken;

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
        $appid
    ) {
        $this->bridge = $bridge;
        $this->provider = $provider;
        $this->appid = $appid;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            $this->userChecker->checkPostAuth($user);

            $newToken = new SocialToken(
                $this->providerKey,
                $user,
                $user->getRoles(),
                $token->getAccessToken()
            );
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        if (!is_null($token->getAccessToken())) {
            $this->facebook->setAccessToken($token->getAccessToken());
        }

        if ($uid = $this->facebook->getUser()) {
            $newToken = $this->createAuthenticatedToken($uid, $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        throw new AuthenticationException('The Facebook user could not be retrieved from the session.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof SocialToken && $this->providerKey === $token->getProviderKey();
    }

    protected function createAuthenticatedToken($uid, $accessToken = null)
    {
        if (null === $this->userProvider) {
            return new SocialToken($this->providerKey, $uid, array(), $accessToken);
        }

        try {
            $user = $this->userProvider->loadUserByUsername($uid);
            if ($user instanceof UserInterface) {
                $this->userChecker->checkPostAuth($user);
            }
        } catch (UsernameNotFoundException $ex) {
            if (!$this->createIfNotExists) {
                throw $ex;
            }

            $user = $this->userProvider->createUserFromUid($uid);
        }

        if (!$user instanceof UserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }

        return new SocialToken($this->providerKey, $user, $user->getRoles(), $accessToken);
    }

}
