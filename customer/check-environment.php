<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<pre>\n";
echo "PHP Version: " . phpversion() . "\n\n";

echo "Server Information:\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "Current Directory: " . getcwd() . "\n\n";

echo "Path Information:\n";
$basePath = dirname(__DIR__);
echo "Base Path: " . $basePath . "\n";
echo "Config Path: " . $basePath . "/config/database-config.php\n";
echo "OAuth Path: " . $basePath . "/config/oauth-config.php\n\n";

echo "File Existence Checks:\n";
$configFiles = [
    'database-config.php' => $basePath . '/config/database-config.php',
    'oauth-config.php' => $basePath . '/config/oauth-config.php'
];

foreach ($configFiles as $name => $path) {
    echo "$name: " . (file_exists($path) ? "Found" : "Not Found") . "\n";
    if (file_exists($path)) {
        echo "Readable: " . (is_readable($path) ? "Yes" : "No") . "\n";
        echo "File permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
    }
    echo "\n";
}

echo "Directory Contents of /config/:\n";
$configDir = $basePath . '/config';
if (is_dir($configDir)) {
    $files = scandir($configDir);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            echo $file . "\n";
        }
    }
} else {
    echo "Config directory not found or not accessible\n";
}

echo "\nInclude Path: " . get_include_path() . "\n";
echo "</pre>";
?>