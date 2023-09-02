<?php

namespace app\helpers;

use PDO;

class ClickHouse
{
	public $conn;
	public function __construct()
	{
		$config = Config::get('clickhouse');
		$this->conn = new PDO("mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}", $config['user'], $config['password']);
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function query($query, $binds = [])
	{
		$q = $this->conn->prepare($query);
		return $q->execute($binds);
	}
}
