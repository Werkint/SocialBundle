<?php
namespace Werkint\Bundle\SocialBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class SocialToken extends AbstractToken
{
    protected $social;
    protected $hash;
    protected $data;

    public function __construct(
        array $roles, $social, $hash, array $data
    ) {
        parent::__construct($roles);

        $this->social = $social;
        $this->hash = $hash;
        $this->data = $data;
    }

    public function serialize()
    {
        return serialize([
            $this->social,
            $this->hash,
            $this->data,
            parent::serialize()
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->social, $this->hash, $this->add, $parent) = unserialize($serialized);
        parent::unserialize($parent);
    }

    public function getCredentials()
    {
        return null;
    }

}