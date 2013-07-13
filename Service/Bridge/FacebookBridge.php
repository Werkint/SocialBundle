<?php
namespace Werkint\Bundle\SocialBundle\Service\Bridge;

/**
 * FacebookBridge.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class FacebookBridge extends \Facebook
{
    protected $parameters;

    public function __construct(
        array $parameters
    ) {
        $this->parameters = $parameters;
        parent::__construct(array(
            'appId'  => $this->parameters['facebook']['appid'],
            'secret' => $this->parameters['facebook']['secret'],
        ));
    }

    public function postFeedMessage($uid, $data)
    {
        return $this->api('/' . $uid . '/feed', 'post', $data);
    }

    public function getUserInfo()
    {
        return $this->api('/' . $this->getUser());
    }

    public function getLoginUrl()
    {
        return parent::getLoginUrl([
            'scope'        => $this->parameters['facebook']['scope'],
            'redirect_uri' => $this->parameters['checkpath'],
            'display'      => 'page',
        ]);
    }

}