<?php
namespace Werkint\Bundle\SocialBundle\Service\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * SocialToken.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SocialToken extends AbstractToken
{
    protected $providerKey;
    protected $hash;
    protected $data;

    public function __construct(
        array $roles, $providerKey, $hash = null, array $data = []
    ) {
        parent::__construct($roles);

        $this->providerKey = $providerKey;
        $this->hash = $hash;
        $this->data = $data;
    }

    public function serialize()
    {
        return serialize([
            $this->providerKey,
            $this->hash,
            $this->data,
            parent::serialize()
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->providerKey, $this->hash, $this->add, $parent) = unserialize($serialized);
        parent::unserialize($parent);
    }

    public function getCredentials()
    {
        return null;
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }

}