<?php

use Elastica\Result;
?>
    <a href="/" class="button"><- HP</a>
	<form action="/elasticSearch">
		<div class="input-group">
			<input class="input-group-field" name="q" minlength="3" value="<?= $q ?>">
			<div class="input-group-button">
				<input type="submit" class="button" value="Submit">
			</div>
		</div>
	</form>
<?php

/** @var int $count */
if($count) {
	/** @var Result $result */
	$resultString = $result->getData()[TermService::TERM_FIELD_NAME];

	/** @var int $fuzziness */
	if($fuzziness === 0) {
		echo "We have found exactly what you have been looking for: <strong>$resultString</strong>";
	} else {
		echo <<<HTML
					We have found something similar: <strong>$resultString</strong> 
					<hr>
					<strong>Fuzziness:</strong> $fuzziness <br>
					<strong>Score:</strong> {$result->getScore()}
HTML;
	}
} else {
	echo 'We have not been able to find anything even remotely similar!';
}