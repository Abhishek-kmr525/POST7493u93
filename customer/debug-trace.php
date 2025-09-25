<?php
// Force error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to catch any errors
ob_start();

try {
    echo "Step 1: Starting execution\n";
    
    $basePath = dirname(__DIR__);
    echo "Base path: $basePath\n";
    
    echo "\nStep 2: Checking config files\n";
    $configPath = $basePath . '/config/database-config.php';
    $oauthPath = $basePath . '/config/oauth-config.php';
    
    echo "Database config exists: " . (file_exists($configPath) ? "Yes" : "No") . "\n";
    echo "OAuth config exists: " . (file_exists($oauthPath) ? "Yes" : "No") . "\n";
    
    echo "\nStep 3: Testing database config include\n";
    try {
        require_once $configPath;
        echo "Database config included successfully\n";
        
        // Test database connection if $db variable exists
        if (isset($db)) {
            echo "Database variable exists\n";
            try {
                $db->query("SELECT 1");
                echo "Database connection successful\n";
            } catch (PDOException $e) {
                echo "Database connection failed: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Database variable not found\n";
        }
    } catch (Throwable $e) {
        echo "Error including database config: " . $e->getMessage() . "\n";
        echo "File contents preview:\n";
        if (is_readable($configPath)) {
            $contents = file_get_contents($configPath);
            echo htmlspecialchars(substr($contents, 0, 500)) . "...\n";
        }
    }
    
    echo "\nStep 4: Testing OAuth config include\n";
    try {
        require_once $oauthPath;
        echo "OAuth config included successfully\n";
        
        // Test if essential OAuth constants are defined
        $requiredConstants = ['GOOGLE_CLIENT_ID', 'LINKEDIN_CLIENT_ID'];
        foreach ($requiredConstants as $constant) {
            echo "$constant defined: " . (defined($constant) ? "Yes" : "No") . "\n";
        }
    } catch (Throwable $e) {
        echo "Error including OAuth config: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 5: Environment Variables\n";
    $safeEnvVars = ['SITE_URL', 'DB_HOST', 'DB_NAME'];
    foreach ($safeEnvVars as $var) {
        echo "$var set: " . (getenv($var) ? "Yes" : "No") . "\n";
    }
    
    echo "\nStep 6: PHP Info\n";
    echo "PHP Version: " . phpversion() . "\n";
    echo "Loaded Extensions:\n";
    $requiredExts = ['pdo', 'pdo_mysql', 'json', 'openssl'];
    foreach ($requiredExts as $ext) {
        echo "$ext: " . (extension_loaded($ext) ? "Loaded" : "Not loaded") . "\n";
    }

} catch (Throwable $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    echo "in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Get the output
$output = ob_get_clean();

// Display as HTML if accessed via browser, otherwise as plain text
if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre>";
    echo htmlspecialchars($output);
    echo "</pre>";
} else {
    echo $output;
}
?>