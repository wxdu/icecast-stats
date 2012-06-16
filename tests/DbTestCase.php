<?php
require_once "PHPUnit/Extensions/Database/TestCase.php";

abstract class DbTestCase extends PHPUnit_Extensions_Database_TestCase {

    static private $pdo = null;

    private $conn = null;

    final public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('sqlite::memory:');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }
        return $this->conn;
    }

}