<?php
namespace Werkint\Bundle\SocialBundle\Social;

require_once('vendor/facebook/facebook.php');

class Facebook extends \Facebook {

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

	public function postFeedMessage($uid, $data) {
		return $this->api('/' . $uid . '/feed', 'post', $data);
	}

	public function getUserInfo() {
		return $this->api('/' . $this->getUser());
	}

}