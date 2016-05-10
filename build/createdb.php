<?php
$config = require __DIR__ . '/../config.php';
echo PHP_EOL . 'Creating schema in: ' . $config['doctrineParams']['path'] . PHP_EOL;
$db = new SQLite3( $config['doctrineParams']['path'] );

$results = $db->query( 'CREATE TABLE `user` (
                        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                        `email` VARCHAR(50) NOT NULL,
                        `forename` CHAR(50) NOT NULL,
                        `surname` CHAR(50),
                        `created` DATETIME
                        );' );

$results = $db->query( "INSERT INTO user VALUES (1,'john@mail.com', 'john', 'doe', '0001-01-01 01:01:01');" );

$results = $db->query( "INSERT INTO user VALUES (2,'john2@mail.com', 'john2', 'doe2', '0001-01-01 01:01:01');" );

echo 'Db created with two users' . PHP_EOL;

$results = $db->query('SELECT * FROM user');
while ($row = $results->fetchArray()) {
    print_r($row);
}
