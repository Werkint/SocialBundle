<?php
namespace Werkint\Bundle\SocialBundle\Social;

use Werkint\Bundle\WebappBundle\Webapp\Webapp;

class Social
{

    protected $parameters;
    protected $webapp;

    public function __construct(
        Webapp $webapp,
        array $parameters
    ) {
        $this->webapp = $webapp;
        $this->parameters = $parameters;
    }

    public function init()
    {
        $this->webapp->addVars([
            'werkint-social-xdpath' => $this->parameters['xdpath'],
            'werkint-social-idfb'   => $this->parameters['facebook']['appid'],
            'werkint-social-idvk'   => $this->parameters['vkontakte']['appid'],
        ]);
        $this->webapp->attachFile(
            __DIR__ . '/../Resources/script/social.js'
        );
    }

}