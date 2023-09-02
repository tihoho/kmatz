<?php

namespace app\helpers;

class Config
{
	public static function get($name)
	{
		$filename = CONFIG_PATH . DIRECTORY_SEPARATOR . $name . '.php';
		if (!file_exists($filename)) {
			throw new \Exception("Config not found in [$filename]");
		}

		return require $filename;
	}
}
