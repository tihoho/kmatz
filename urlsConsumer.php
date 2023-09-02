<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configs/main.php';

use app\helpers\Config;
use app\helpers\MySQL;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$urls = Config::get('urls');
$rmqConfig = Config::get('rabbitmq');

$connection = new AMQPStreamConnection(
	$rmqConfig['host'],
	$rmqConfig['port'],
	$rmqConfig['user'],
	$rmqConfig['password']
);
$channel = $connection->channel();

$channel = $connection->channel();

// Объявляем очередь
$channel->queue_declare(QUEUE_NAME, false, true, false, false);

echo "Wait messages..." . PHP_EOL;

$mysql = (new MySQL())->conn;

// Consumer
$callback = function ($message) use ($mysql) {

    $url = $message->body;
    
    $content = file_get_contents($url);
    $len = mb_strlen($content);

	$stmt = $mysql->prepare('INSERT INTO urls (url, content_len) VALUES (?, ?)');
	$stmt->execute([$url, $len]);

    echo "URL: {$url}, COUNT: {$len}" . PHP_EOL;

	$message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
};

// Максимум сообщений на одного consumer'a
$channel->basic_qos(null, 1, null);

// Привязываем consumer'a к очереди
$channel->basic_consume(QUEUE_NAME, '', false, false, false, false, $callback);

// Слушаем новые сообщения
while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
