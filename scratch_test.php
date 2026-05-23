<?php
$host = '127.0.0.1';
$port = '33060';
$db   = 'ta_stunting';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, child_id, nama, status, created_at FROM detections");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Detection ID: " . $row['id'] 
            . " | Child ID: " . $row['child_id'] 
            . " | Child Name: " . $row['nama'] 
            . " | Status: " . $row['status'] 
            . " | Created At: " . $row['created_at'] 
            . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
}
