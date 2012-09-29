<?php
namespace Werkint\Bundle\SocialBundle\Social;

require_once('vendor/vkapi.class.php');

class Vkontakte extends \vkapi {

	public static $param_appId;
	public static $param_secret;

	public function __construct() {
		if (!(static::$param_appId && static::$param_secret)) {
			throw new \Exception('App data is not set');
		}
		parent::__construct(static::$param_appId, static::$param_secret);
	}

	public function getProfile($uid) {
		$resp = $this->api('getProfiles', array('uids'=> $uid, 'fields' => 'uid, first_name, last_name, nickname'));
		$resp = $resp['response'];
		return count($resp) && $resp ? $resp[0] : null;
	}

}