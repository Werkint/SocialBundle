<?php
namespace Werkint\Bundle\SocialBundle\Service\Bridge;

use Vkapi\Vkapi;

/**
 * VkontakteBridge.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class VkontakteBridge extends Vkapi
{
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

    public function getLoginUrl()
    {
        $params = [
            'client_id'     => $this->app_id,
            'scope'         => $this->parameters['vkontakte']['scope'],
            'redirect_uri'  => $this->parameters['checkpath'],
            'response_type' => 'code',
            'display'       => 'page',
        ];
        return 'https://oauth.vk.com/authorize?' . http_build_query($params);
    }

}