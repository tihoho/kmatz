<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configs/main.php';

use app\helpers\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$urls = Config::get('urls');
$rmqConfig = Config::get('rabbitmq');

$connection = new AMQPStreamConnection(
	$rmqConfig['host'],
	$rmqConfig['port'],
	$rmqConfig['user'],
	$rmqConfig['password']
);
$channel = $connection->channel();

$channel->queue_declare(QUEUE_NAME, false, true, false, false);

foreach ($urls as $url) {

	$sleep = random_int(5, 30);

	echo "Adding $url after $sleep sec...";

	sleep($sleep);

	$message = new AMQPMessage($url);

	$channel->basic_publish($message, '', QUEUE_NAME);

	echo 'OK' . PHP_EOL;
}

$channel->close();
$connection->close();
