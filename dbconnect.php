<?php
    if (!isset($_SESSION)) session_start();
    class DBConnection {
        private static $connection = null;
        
        private function __construct() {
            try {
                self::$connection = mysqli_connect("localhost", "root", "", "register");            
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        public static function getDatabaseConnection() {
            if (self::$connection == null) {
                new DBConnection();
            }
            return self::$connection;
        }
    }
    
    // $connection = DBConnection::getDatabaseConnection();
