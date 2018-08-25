<?php

use Elastica\Document;
use Elastica\Query;
use Elastica\Query\Match;

/**
 * Manages all comunication with Elastic
 *
 * Class ElasticService
 */
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

	const TYPE_NAME = '_doc';
	const INDEX_NAME = 'terms';

	/**
	 * @var \Elastica\Client
	 */
	protected $_client;

	/**
	 * @var \Elastica\Index
	 */
	protected $_index;

	/**
	 * @var \Elastica\Type
	 */
	protected $_type;


	public function __construct()
	{
		$this->_client = new Elastica\Client(self::SETTINGS);
		$this->_index = $this->_client->getIndex(self::INDEX_NAME);
		$this->_type = $this->_index->getType(self::TYPE_NAME);
	}

	/**
	 * Only check that i have OK status
	 *
	 * @return int
	 */
	public function isConnectionOk()
	{
		return $this->_client->getStatus()->getResponse()->isOk();
	}

	/**
	 * Přidá záznam v indexu
	 *
	 * @param int $id Id in index
	 * @param string $term Original term
	 * @param string $term_standardized Term without accent in lowercase
	 *
	 * @return \Elastica\Response
	 */
	public function addTerm(int $id, string $term, string $term_standardized)
	{
		/**
		 * If index  is not yet present, create it
		 */
		try {
			$mapping = $this->_type->getMapping();
		} catch (\Elastica\Exception\ResponseException $e) {
			$this->_index
				->create([
					'mappings' => [
						self::TYPE_NAME => [
							'properties' => [
								TermService::TERM_FIELD_NAME => [
									'type' => 'text',
//									'analyzer' => 'keyword'
								],
								TermService::STANDARDIZED_TERM_FIELD_NAME => [
									'type' => 'text',
//									'analyzer' => 'keyword'
								]
							]
						]
					]
				]);
		}

		$document = new Document(
			$id,
			[
				TermService::TERM_FIELD_NAME => $term,
				TermService::STANDARDIZED_TERM_FIELD_NAME => $term_standardized
			]
		);

		return $this->_type->addDocument($document);
	}

	/**
	 * Delete all indices and data from elastic
	 */
	public function formatElastic()
	{
		$this->_client->request('_all', Elastica\Request::DELETE); // Erases elastic
	}

	/**
	 * It returns string to output for query
	 *
	 * @param $q
	 * @return array
	 */
	public function getResultForQuery($q)
	{
		$match = new Match(TermService::STANDARDIZED_TERM_FIELD_NAME);
		$match->setFieldQuery(TermService::STANDARDIZED_TERM_FIELD_NAME, $q);

		$resultCount = 0;
		$fuzziness = 0;

		/**
		 * First, we will try only very similar result and then widen it
		 */
		while ($resultCount < 1 && $fuzziness < 10) {
			$match->setFieldFuzziness(TermService::STANDARDIZED_TERM_FIELD_NAME, $fuzziness);
			$query = new Query($match);
			$query->setSize(1);
			$searchResult = $this->_type->search($query);
			$resultCount = $searchResult->count();
			$fuzziness++;
		}

		if ($resultCount) {
			$result = $searchResult->getResults()[0];
		} else {
			$result = null;
		}

		return [
			'count' => $resultCount,
			'result' => $result,
			'fuzziness' => $fuzziness
		];
	}
}