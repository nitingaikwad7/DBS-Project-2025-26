<?php
/**
 * Database Connection Test
 * Check if everything is working
 */

echo "<h1>Online Job Portal - System Check</h1>";
echo "<hr>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo (version_compare(phpversion(), '7.0.0', '>=')) ? "✅ PHP version is OK<br>" : "❌ PHP version too old<br>";

// Test 2: Database Connection
echo "<h2>2. Database Connection</h2>";
$conn = @mysqli_connect('localhost', 'root', '', 'job_portal');

if ($conn) {
    echo "✅ Database connection successful!<br>";
    echo "Database: job_portal<br>";
    
    // Test 3: Check Tables
    echo "<h2>3. Database Tables</h2>";
    $tables = ['users', 'job_seekers', 'employers', 'jobs', 'applications', 'admin'];
    
    foreach ($tables as $table) {
        $result = @mysqli_query($conn, "SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "✅ Table '$table' exists - {$row['count']} records<br>";
        } else {
            echo "❌ Table '$table' NOT FOUND<br>";
        }
    }
    
    // Test 4: Check Sample Data
    echo "<h2>4. Sample Data Check</h2>";
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] > 0) {
        echo "✅ Sample data exists - {$row['count']} users<br>";
        
        // Show sample users
        $result = mysqli_query($conn, "SELECT email, user_type FROM users LIMIT 5");
        echo "<br><strong>Sample Users:</strong><br>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Email</th><th>Type</th></tr>";
        while ($user = mysqli_fetch_assoc($result)) {
            echo "<tr><td>{$user['email']}</td><td>{$user['user_type']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No sample data found. Please import complete_database.sql<br>";
    }
    
    mysqli_close($conn);
} else {
    echo "❌ Database connection FAILED!<br>";
    echo "Error: " . mysqli_connect_error() . "<br><br>";
    echo "<strong>Possible Solutions:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP<br>";
    echo "2. Create database 'job_portal' in phpMyAdmin<br>";
    echo "3. Import complete_database.sql file<br>";
    echo "4. Check config.php settings<br>";
}

// Test 5: File Permissions
echo "<h2>5. File Check</h2>";
$files = ['index.php', 'login.php', 'config.php', 'browse_jobs.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file NOT FOUND<br>";
    }
}

// Test 6: Session
echo "<h2>6. Session Test</h2>";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['test'] = 'working';
echo (isset($_SESSION['test'])) ? "✅ Sessions are working<br>" : "❌ Sessions not working<br>";

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If all tests pass, visit: <a href='index.php'>Home Page</a></li>";
echo "<li>If database connection failed, check XAMPP MySQL is running</li>";
echo "<li>If tables not found, import complete_database.sql in phpMyAdmin</li>";
echo "<li>If files not found, make sure all files are in htdocs/jobportal/</li>";
echo "</ol>";

echo "<br><a href='index.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Go to Home Page</a>";
?>
