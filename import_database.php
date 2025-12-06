<?php
/**
 * Database Import Script
 * Automatically imports the database
 */

set_time_limit(300); // 5 minutes timeout

echo "<h1>Database Import Tool</h1>";
echo "<hr>";

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'job_portal';

// Connect to MySQL
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

echo "✅ Connected to MySQL<br><br>";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    echo "✅ Database '$dbname' created/exists<br>";
} else {
    echo "❌ Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select database
mysqli_select_db($conn, $dbname);
echo "✅ Database selected<br><br>";

// Read SQL file
$sqlFile = 'complete_database.sql';

if (!file_exists($sqlFile)) {
    die("❌ SQL file not found: $sqlFile<br>Make sure complete_database.sql is in the same folder as this script.");
}

echo "📄 Reading SQL file...<br>";
$sql = file_get_contents($sqlFile);

if ($sql === false) {
    die("❌ Could not read SQL file<br>");
}

echo "✅ SQL file loaded (" . strlen($sql) . " bytes)<br><br>";

// Split into individual queries
$queries = array_filter(array_map('trim', explode(';', $sql)));

echo "📊 Executing " . count($queries) . " queries...<br><br>";

$success = 0;
$errors = 0;

foreach ($queries as $query) {
    if (empty($query)) continue;
    
    if (mysqli_query($conn, $query)) {
        $success++;
    } else {
        $errors++;
        // Only show first 5 errors
        if ($errors <= 5) {
            echo "⚠️ Error in query: " . substr($query, 0, 50) . "...<br>";
            echo "Error: " . mysqli_error($conn) . "<br><br>";
        }
    }
}

echo "<hr>";
echo "<h2>Import Summary:</h2>";
echo "✅ Successful queries: $success<br>";
echo "❌ Failed queries: $errors<br><br>";

if ($errors == 0) {
    echo "<h3 style='color: green;'>🎉 Database imported successfully!</h3>";
    
    // Verify data
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "👥 Total users: {$row['count']}<br>";
    }
    
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "💼 Total jobs: {$row['count']}<br>";
    }
    
    echo "<br><br>";
    echo "<a href='test_connection.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Test Connection</a> ";
    echo "<a href='index.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Go to Home Page</a>";
} else {
    echo "<h3 style='color: orange;'>⚠️ Import completed with some errors</h3>";
    echo "<p>Some queries failed, but the database might still work. Try testing the connection.</p>";
    echo "<a href='test_connection.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Test Connection</a>";
}

mysqli_close($conn);
?>
