<?php
// Test script to verify database connection fix
require_once 'backend/Database/db.php';

echo "<h2>Database Connection Test</h2>";

// Check system requirements
echo "<h3>System Requirements Check:</h3>";
$requirements = Db::checkRequirements();
foreach ($requirements as $requirement => $status) {
    $status_text = $status ? '✓ Available' : '✗ Missing';
    $color = $status ? 'green' : 'red';
    echo "<p style='color: $color;'>$requirement: $status_text</p>";
}

echo "<h3>Connection Test:</h3>";

try {
    // Test database connection
    $db = new Db();
    $connection = $db->getConnection();
    
    if ($connection) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        
        // Test a simple query
        $stmt = $connection->query("SELECT 1 as test");
        $result = $stmt->fetch();
        
        if ($result && $result['test'] == 1) {
            echo "<p style='color: green;'>✓ Database query test successful!</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Database query test failed</p>";
        }
        
        // Test charset
        $stmt = $connection->query("SELECT @@character_set_connection as charset");
        $charset = $stmt->fetch();
        echo "<p>Current charset: " . htmlspecialchars($charset['charset']) . "</p>";
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>PHP Version and Extensions:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO Available: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "</p>";
echo "<p>PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</p>";
echo "<p>MySQL ATTR INIT COMMAND Defined: " . (defined('PDO::MYSQL_ATTR_INIT_COMMAND') ? 'Yes' : 'No') . "</p>";
?>
