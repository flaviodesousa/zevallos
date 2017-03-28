<?php
echo '<table border="1">';

foreach ($_SERVER as $key => $value) {
	echo "<tr><td>$key</td><td>$value</td></tr>";
}
echo '</table>';
?>