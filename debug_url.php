<?php
require_once "Config/Config.php";
echo "<h3>Debug Base URL</h3>";
echo "PROTOCOL: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'OFF') . "<br>";
echo "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NONE') . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "COMPUTED base_url: " . base_url . "<br>";
echo "ENV BASE_URL: " . getenv('BASE_URL') . "<br>";
?>
