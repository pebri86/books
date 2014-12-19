<?php
namespace Library;

class Config {
	static public $directory;
	static public $config = array();

	static public function setDirectory($path) {
		self::$directory = $path;
	}

	static public function get($config) {
		$config = strtolower($config);

		self::$config[$config] =
		require self::$directory . '/' . $config . '.php';

		return self::$config[$config];
	}

}
?>