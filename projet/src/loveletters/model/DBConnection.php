<?php
namespace loveletters\model;
use Illuminate\Database\Capsule\Manager as DB;

class DBConnection{
	private static $_instance = null;
	
	private function __construct() {
		$config=parse_ini_file('src/conf/conf.ini');
		
		$db = new DB();
		$db->addConnection( [
				'driver' => 'mysql',
				'host' => 'localhost',
				'database' => $config['dsn'],
				'username' => $config['user'],
				'password' => $config['password'],
				'charset' => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix' => ''
		] );
		$db->setAsGlobal();
		$db->bootEloquent();
	}
	
	public static function getInstance() {
	
		if(is_null(self::$_instance)) {
			self::$_instance = new DBConnection();
		}
	
		return self::$_instance;
	}
	
}