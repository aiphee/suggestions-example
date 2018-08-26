<a href="/" class="button"><- HP</a>
<form action="/levenshteinSearch">
    <div class="input-group">
        <input class="input-group-field" name="q" minlength="3" value="<?= $q ?>">
        <div class="input-group-button">
            <input type="submit" class="button" value="Submit">
        </div>
    </div>
</form>
<?php

if ($result) {
	echo <<<HTML
        We have found something similar: <strong>$result</strong> 
        <hr>
        <strong>Score:</strong> $score (lower is better)
HTML;
} else {
	echo 'We have not been able to find anything even remotely similar!';
}