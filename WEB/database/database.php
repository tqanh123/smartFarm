<?php
// $host="192.168.56.1";
// $dbuser="root@localhost";
// $dbpass="";
// $db="controlweb";
// $conn=new mysqli($host,$dbuser, $dbpass, $db);
	class Database {
		private static $dbName = 'controlweb'; // Example: private static $dbName = 'myDB';
		private static $dbHost = 'localhost'; // Example: private static $dbHost = 'localhost';
		private static $dbUsername = 'root'; // Example: private static $dbUsername = 'myUserName';
		private static $dbUserPassword = ''; // // Example: private static $dbUserPassword = 'myPassword';
		 
		private static $conn  = null;
		 
		public function __construct() {
			die('Init function is not allowed');
		}
		 
		public static function connect() {
            // One connection through whole application
            if ( null == self::$conn ) {     
                try {
                self::$conn =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
                } catch(PDOException $e) {
                die($e->getMessage()); 
                }
            }
            return self::$conn;
		}
		 
		public static function disconnect() {
			self::$conn = null;
		}
	}
?>