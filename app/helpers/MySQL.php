<?php

namespace app\helpers;

class MySQL
{
	public $conn;
	public function __construct()
	{
		$config = Config::get('mysql');
		$this->conn = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public function query($query)
	{
		return $this->conn->query($query);
	}
}
