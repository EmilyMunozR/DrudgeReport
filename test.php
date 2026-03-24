<?php
$fp = fsockopen("in-v3.mailjet.com", 465, $errno, $errstr, 10);
if (!$fp) {
    echo "Error: $errstr ($errno)";
} else {
    echo "Conexión OK";
    fclose($fp);
}
?>
