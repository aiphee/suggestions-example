<?php

use CodeInc\StripAccents\StripAccents;

/**
 * Does operations with terms
 *
 * Class TermService
 */
class TermService
{
	const
		TERM_FIELD_NAME = 'term',
		STANDARDIZED_TERM_FIELD_NAME = 'term_standardized';

	/**
	 * @var PDO
	 */
	protected $_db;

	public function __construct()
	{
		$this->_db = Flight::db();
	}

	public function getTermCount(): int
	{
		$statement = $this->_db->query('SELECT COUNT(*) FROM terms');

		return (int)$statement->fetchColumn();
	}

	public function uploadTerms(string $termUploadLocation)
	{
		$ElasticService = new ElasticService;
		$skipped = [];
		$file = new SplFileObject($termUploadLocation);

		while (!$file->eof()) {
			$line = strip_tags($file->fgets());
			if (strlen($line) > 255) {
				$skipped[] = "line {$file->getCurrentLine()} too long";
			} else {
				$line_standardized = strtolower(StripAccents::strip($line));

				$this->_addToDb($line, $line_standardized); // Add to mysql

				$id = $this->_db->lastInsertId();

				$ElasticService->addTerm($id, $line, $line_standardized); // Add to elastic
			}
		}
	}


	/**
	 * Insert row to mysql
	 *
	 * @param string $line
	 * @param string $line_standardized
	 * @return bool
	 */
	protected function _addToDb(string $line, string $line_standardized): bool
	{

		$statement = $this->_db->prepare("INSERT INTO `terms` (`term`, `term_standardized`) VALUES (:term, :term_standardized);");

		$result = $statement->execute([
			self::TERM_FIELD_NAME => $line,
			self::STANDARDIZED_TERM_FIELD_NAME => $line_standardized,
		]);

		return $result;
	}

	/**
	 * It may iterate over large portion of database and searches for closest match, should be slow for many results
	 * @param string $q
	 * @return array
	 */
	public function getLevenshteinResult(string $q): array
	{
		$highestMatchRow = false;
		$lowestScore = 999;
		$q = strtolower(StripAccents::strip($q));
		$len = strlen($q);
		$min_len = floor($len * .75);
		$max_len = floor($len * 1.25);

		$sql = <<<SQL
SELECT * FROM terms 
WHERE 
	(LENGTH(term_standardized) BETWEEN :min_len AND :max_len);
SQL;
		$statement = $this->_db->prepare($sql);

		$statement->execute([
			'min_len' => $min_len,
			'max_len' => $max_len
		]);

		while ($result = $statement->fetch(PDO::FETCH_ASSOC)) {
			$score = levenshtein($result['term_standardized'], $q);
			if ($score < $lowestScore) {
				$lowestScore = $score;
				$highestMatchRow = $result['term'];

				/**
				 * No need to check further
				 */
				if ($lowestScore === 1) {
					break;
				}
			}
		}

		return [
			'score' => $lowestScore,
			'result' => $highestMatchRow
		];
	}
}