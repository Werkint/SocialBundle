<?php
namespace Werkint\Bundle\SocialBundle\Service\Provider;

use Vkapi\Vkapi;

class VkontakteProvider extends Vkapi
{

    public static $param_appId;
    public static $param_secret;

    protected $parameters;

    public function __construct(
        array $parameters
    ) {
        $this->parameters = $parameters;
        parent::__construct(
            $this->parameters['vkontakte']['appid'],
            $this->parameters['vkontakte']['secret']
        );
    }

    public function getProfile($uid)
    {
        $resp = $this->api(
            'getProfiles',
            ['uids' => $uid, 'fields' => 'uid, first_name, last_name, nickname']
        );
        $resp = $resp['response'];
        return count($resp) && $resp ? $resp[0] : null;
    }

    public function getUserInfo($uid)
    {
        return $this->getProfile($uid);
    }

}