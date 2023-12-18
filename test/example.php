<?php

require_once("../src/edjsHTML.php");
$data = file_get_contents("./data.json");
$result = edjsHTML::parse_strict($data);
$result = array_map(function ($section) {
	return "<section>$section</section>";
}, $result);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Example</title>
</head>
<body>
	<?= implode("", $result) ?>
</body>
</html>