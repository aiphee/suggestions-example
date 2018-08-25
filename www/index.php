<?php 
require 'vendor/autoload.php';

Flight::map('error', function(Throwable $e) {
	echo '<h1>' . get_class($e) . '</h1>';
	echo "<p>{$e->getMessage()}</p>";
	echo "<pre>{$e->getTraceAsString()}</pre>";
});

Flight::register('db', 'PDO', array('mysql:host=mysql;dbname=test;charset=utf8','root','pswd'));

/**
 * Homepage
 */
Flight::route('/', function(){
	$TermService = new TermService;

	/**
	 * Check if Elastic is OK so we can fill it with data
	 */
	try {
		$ElasticService = new ElasticService();
		if(!$ElasticService->isConnectionOk()) {
			throw new RuntimeException('Elastic is connected but is NOT OK');
		}

		$termUploadLocation = Flight::request()->files->getData()['term_file']['tmp_name'] ?? false;
		if($termUploadLocation) {
			$TermService->uploadTerms($termUploadLocation);
		}
	} catch (\Elastica\Exception\Connection\HttpException $e) {
		throw new RuntimeException('Elastic could not be reached');
	}

	/**
	 * Check if any data present, if not upload some
	 */
	if($TermService->getTermCount() == 0) {
		Flight::render('elements/upload_new', [], 'content');
	} else {
		Flight::render('elements/hp', [], 'content');
	}

	Flight::render('main');
});


/**
 * Show results from elastic
 */
Flight::route('/elasticSearch', function() {
	$ElasticService = new ElasticService();
	$q = Flight::request()->query['q'] ?? null;
	$q = htmlspecialchars($q, ENT_QUOTES); // CSRF

	$result = $ElasticService->getResultForQuery($q);

	Flight::render('elements/elasticResults', $result + ['q' => $q], 'content');

	Flight::render('main');
});


Flight::start();
