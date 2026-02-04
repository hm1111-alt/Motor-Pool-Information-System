<?php
require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=motorpool_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DATABASE TABLES ===\n";
    $stmt = $pdo->query('SHOW TABLES');
    while ($row = $stmt->fetch()) {
        echo $row[0] . "\n";
    }
    
    echo "\n=== OFFICES COUNT ===\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM offices');
    $result = $stmt->fetch();
    echo "Offices: " . $result['count'] . "\n";
    
    echo "\n=== LIB_DIVISIONS COUNT ===\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM lib_divisions');
    $result = $stmt->fetch();
    echo "Divisions: " . $result['count'] . "\n";
    
    echo "\n=== LIB_UNITS COUNT ===\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM lib_units');
    $result = $stmt->fetch();
    echo "Units: " . $result['count'] . "\n";
    
    echo "\n=== LIB_SUBUNITS COUNT ===\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM lib_subunits');
    $result = $stmt->fetch();
    echo "Subunits: " . $result['count'] . "\n";
    
    echo "\n=== LIB_CLASS COUNT ===\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM lib_class');
    $result = $stmt->fetch();
    echo "Classes: " . $result['count'] . "\n";
    
    echo "\n=== LIB_DIVISIONS STRUCTURE ===\n";
    $stmt = $pdo->query('DESCRIBE lib_divisions');
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . ' (' . $row['Type'] . ")\n";
    }
    
    echo "\n=== LIB_UNITS STRUCTURE ===\n";
    $stmt = $pdo->query('DESCRIBE lib_units');
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . ' (' . $row['Type'] . ")\n";
    }
    
    echo "\n=== LIB_SUBUNITS STRUCTURE ===\n";
    $stmt = $pdo->query('DESCRIBE lib_subunits');
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . ' (' . $row['Type'] . ")\n";
    }
    
    echo "\n=== LIB_CLASS STRUCTURE ===\n";
    $stmt = $pdo->query('DESCRIBE lib_class');
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . ' (' . $row['Type'] . ")\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}