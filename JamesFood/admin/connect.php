<?php
define('DB_DSN', 'mysql:host=localhost;dbname=dbjames;charset=utf8');
define('DB_USER', 'usrjames');
define('DB_PASS', 'k7eRb4@sQ');

try {
    $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ            
    );
    $db = new PDO(DB_DSN, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}