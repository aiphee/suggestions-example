<?php 
require 'vendor/autoload.php';

Flight::map('error', function(Throwable $e) {
	echo '<h1>' . get_class($e) . '</h1>';
	echo "<p>{$e->getMessage()}</p>";
	echo "<pre>{$e->getTraceAsString()}</pre>";
});

Flight::register('db', 'PDO', array('mysql:host=mysql;dbname=test','root','pswd'));


Flight::route('/', function(){
	try {
		$ElasticService = new ElasticService();
		if(!$ElasticService->isConnectionOk()) {
			throw new RuntimeException('Elastic is connected but is NOT OK');
		}
	} catch (\Elastica\Exception\Connection\HttpException $e) {
		throw new RuntimeException('Elastic could not be reached');
	}

	Flight::render('elements/hp', [], 'content');
	Flight::render('main');
});


Flight::start();
