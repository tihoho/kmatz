<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configs/main.php';

use app\helpers\ClickHouse;
use app\helpers\Config;
use app\helpers\MySQL;

$mysql = (new MySQL())->conn;
$clickhouse = (new ClickHouse())->conn;

$qMysql = <<<SQL
CREATE TABLE `urls` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`url` VARCHAR(512) NOT NULL COLLATE 'utf8_general_ci',
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`content_len` INT(11) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
SQL;

try {
    $mysql->exec($qMysql);
} catch (PDOException $e) {
    echo 'MySQL Exception: ' . $e->getMessage();
}

$configMysql = Config::get('mysql');

$qClickhouse = <<<SQL
DROP TABLE `urls`;
CREATE TABLE urls (
	id UInt64,
	url String,
	created_at DateTime DEFAULT now(),
	content_len UInt64
  )
  ENGINE = MySQL('mariadb','{$configMysql['dbname']}','urls','{$configMysql['user']}','{$configMysql['password']}')
SQL;

try {
    $clickhouse->exec($qClickhouse);
} catch (PDOException $e) {
    echo 'ClickHouse Exception: ' . $e->getMessage();
}