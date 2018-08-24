<?php

class ElasticService
{
	const SETTINGS = [
		'connections' => [
			[
				'host' => 'elastic',
				'port' => 9200
			]
		]
	];

	protected $_client;


	public function __construct()
	{
		$this->_client = new Elastica\Client(self::SETTINGS);
	}

	/**
	 * Only check that i have OK status
	 * @return int
	 */
	public function isConnectionOk()
	{
		return $this->_client->getStatus()->getResponse()->isOk();
	}
}