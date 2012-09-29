<?php
namespace Werkint\Bundle\SocialBundle\Social;
use Werkint\Bundle\WebappBundle\Webapp\Webapp;

class Service {

	protected $parameters;
	protected $webapp;

	public function __construct(
		Webapp $webapp,
		array $parameters
	) {
		$this->webapp = $webapp;
		$this->parameters = $parameters;
	}

	public function init() {
		$this->webapp->addVar('werkint-social-xdpath', $this->parameters['xdpath']);
		$this->webapp->addVar('werkint-social-idfb', $this->parameters['socials']['facebook']['id']);
		$this->webapp->addVar('werkint-social-xdpath', $this->parameters['socials']['vkontakte']['id']);
		$this->webapp->attachFile(__DIR__ . '/../Resources/script/social.js');
		Facebook::$param_appId = $this->parameters['socials']['facebook']['id'];
		Facebook::$param_secret = $this->parameters['socials']['facebook']['secret'];
		Vkontakte::$param_appId = $this->parameters['socials']['vkontakte']['id'];
		Vkontakte::$param_secret = $this->parameters['socials']['vkontakte']['secret'];
	}

}