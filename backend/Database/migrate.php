<?php

/**
 * Database Connection Migration Script
 * 
 * This script helps you migrate from multiple database connections
 * to a single centralized connection system.
 * 
 * Run this script to see which files need to be updated.
 */

echo "<h2>Database Connection Migration Report</h2>";
echo "<p>This script will help you identify files that need to be updated to use the centralized database connection.</p>";

// Function to scan directory for PHP files
function scanDirectory($dir, $excludeDirs = [])
{
    $files = [];
    $excludeDirs = array_merge($excludeDirs, ['vendor', 'node_modules', '.git']);

    if (!is_dir($dir)) return $files;

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . '/' . $item;

        if (is_dir($path)) {
            if (!in_array($item, $excludeDirs)) {
                $files = array_merge($files, scanDirectory($path, $excludeDirs));
            }
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $files[] = $path;
        }
    }

    return $files;
}

// Function to check file content
function checkFile($filePath)
{
    $content = file_get_contents($filePath);
    $issues = [];

    // Check for new Db() calls
    if (preg_match('/new\s+Db\s*\(\s*\)/', $content)) {
        $issues[] = 'Uses "new Db()" - should be updated to use centralized connection';
    }

    // Check for include vs require_once
    if (preg_match('/include\s+[\'"]\.\.\/Database\/db\.php[\'"]/', $content)) {
        $issues[] = 'Uses "include" - should be "require_once" for database connection';
    }

    // Check if file includes the database file
    if (
        !preg_match('/require_once\s+[\'"]\.\.\/Database\/db\.php[\'"]/', $content) &&
        !preg_match('/require_once\s+[\'"]Database\/db\.php[\'"]/', $content) &&
        !preg_match('/require_once\s+[\'"]\.\/Database\/db\.php[\'"]/', $content)
    ) {
        $issues[] = 'Missing database include - needs to include database connection file';
    }

    return $issues;
}

// Start scanning from the root directory
$rootDir = dirname(dirname(__DIR__)); // Go up two levels from backend/Database
$phpFiles = scanDirectory($rootDir);

echo "<h3>Scanning {$rootDir} for PHP files...</h3>";
echo "<p>Found " . count($phpFiles) . " PHP files</p>";

$filesWithIssues = [];
$totalIssues = 0;

foreach ($phpFiles as $file) {
    $relativePath = str_replace($rootDir . '/', '', $file);
    $issues = checkFile($file);

    if (!empty($issues)) {
        $filesWithIssues[] = [
            'file' => $relativePath,
            'issues' => $issues
        ];
        $totalIssues += count($issues);
    }
}

// Display results
if (empty($filesWithIssues)) {
    echo "<div style='color: green; padding: 20px; background: #e8f5e8; border: 1px solid #4caf50; border-radius: 5px;'>";
    echo "<h3>✅ All files are using the centralized database connection!</h3>";
    echo "<p>Your website is already optimized for single database connection usage.</p>";
    echo "</div>";
} else {
    echo "<div style='color: orange; padding: 20px; background: #fff3e0; border: 1px solid #ff9800; border-radius: 5px;'>";
    echo "<h3>⚠️ Found {$totalIssues} issues in " . count($filesWithIssues) . " files</h3>";
    echo "<p>These files need to be updated to use the centralized database connection system.</p>";
    echo "</div>";

    echo "<h3>Files that need updates:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f5f5f5;'><th style='padding: 10px; text-align: left;'>File</th><th style='padding: 10px; text-align: left;'>Issues</th></tr>";

    foreach ($filesWithIssues as $fileInfo) {
        echo "<tr>";
        echo "<td style='padding: 10px;'><strong>{$fileInfo['file']}</strong></td>";
        echo "<td style='padding: 10px;'>";
        echo "<ul style='margin: 0; padding-left: 20px;'>";
        foreach ($fileInfo['issues'] as $issue) {
            echo "<li>{$issue}</li>";
        }
        echo "</ul>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>How to fix:</h3>";
    echo "<ol>";
    echo "<li><strong>Replace database includes:</strong> Change <code>include '../Database/db.php'</code> to <code>require_once '../Database/db.php'</code></li>";
    echo "<li><strong>Replace connection creation:</strong> Change <code>\$db = new Db(); \$connection = \$db->connect();</code> to <code>\$connection = getDB();</code></li>";
    echo "<li><strong>Test thoroughly:</strong> Ensure all database operations work correctly after changes</li>";
    echo "</ol>";

    echo "<h3>Example before and after:</h3>";
    echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
    echo "<h4>Before (Multiple connections):</h4>";
    echo "<pre style='background: white; padding: 10px; border-radius: 3px;'>";
    echo "include '../Database/db.php';\n";
    echo "\$db = new Db();\n";
    echo "\$connection = \$db->connect();\n";
    echo "</pre>";

    echo "<h4>After (Single connection):</h4>";
    echo "<pre style='background: white; padding: 10px; border-radius: 3px;'>";
    echo "require_once '../Database/db.php';\n";
    echo "\$connection = getDB();\n";
    echo "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> This script only identifies potential issues. Always test your website thoroughly after making changes.</p>";
echo "<p><strong>Recommendation:</strong> Update files one by one and test each change to ensure everything works correctly.</p>";
