<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Corrections demo</title>
</head>
<body>
<div class="grid-container">
	<?= $content ?>
    <hr>
    <div class="primary label">
		<?php
		$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

		echo "Time: $time seconds";
		?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js"></script>
</body>
</html>