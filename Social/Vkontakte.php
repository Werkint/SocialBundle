<?php
namespace Werkint\Bundle\SocialBundle\Social;

require_once('vendor/vkapi.class.php');

class Vkontakte extends \vkapi {

	public static $param_appId;
	public static $param_secret;

	protected $parameters;

	public function __construct(
		array $parameters
	) {
		$this->parameters = $parameters;
		parent::__construct(
			$this->parameters['socials']['vkontakte']['id'],
			$this->parameters['socials']['vkontakte']['secret']
		);
	}

	public function getProfile($uid) {
		$resp = $this->api('getProfiles', array('uids'=> $uid, 'fields' => 'uid, first_name, last_name, nickname'));
		$resp = $resp['response'];
		return count($resp) && $resp ? $resp[0] : null;
	}

	public function getUserInfo($uid) {
		return $this->getProfile($uid);
	}

}