<?php

$config = require __DIR__ . '/../config.php';
$db = new SQLite3( $config['doctrineParams']['path'] );

$results = $db->query('SELECT * FROM user');
while ($row = $results->fetchArray()) {
    print_r($row);
}
