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
		$this->webapp->addVar('werkint-social-idvk', $this->parameters['socials']['vkontakte']['id']);
		$this->webapp->attachFile(__DIR__ . '/../Resources/script/social.js');
	}

}