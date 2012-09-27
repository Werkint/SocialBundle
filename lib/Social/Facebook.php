<?php
namespace Social\Vkontakte;

require_once('vendor/facebook/facebook.php');

class Facebook extends \Facebook {

	public static $param_appId;
	public static $param_secret;

	public function __construct() {
		if (!(static::$param_appId && static::$param_secret)) {
			throw new \Exception('App data is not set');
		}
		parent::__construct(array(
			'appId'  => static::$param_appId,
			'secret' => static::$param_secret,
		));
	}

}