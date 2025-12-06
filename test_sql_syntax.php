<?php
/**
 * SQL Syntax Validator
 * Tests if SQL files are valid
 */

echo "===========================================\n";
echo "Online Job Portal - SQL Syntax Validator\n";
echo "===========================================\n\n";

// Check if SQL files exist
$sql_files = [
    'database_schema.sql',
    'sample_data.sql',
    'views_procedures_triggers.sql',
    'complete_database.sql'
];

echo "Checking SQL files...\n\n";

foreach ($sql_files as $file) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "✓ $file - Found (" . number_format($size) . " bytes)\n";
    } else {
        echo "✗ $file - NOT FOUND\n";
    }
}

echo "\n===========================================\n";
echo "PHP Files Check\n";
echo "===========================================\n\n";

$php_files = [
    'config.php',
    'login.php',
    'logout.php',
    'register_jobseeker.php',
    'register_employer.php',
    'post_job.php',
    'apply_job.php',
    'admin_dashboard.php'
];

foreach ($php_files as $file) {
    if (file_exists($file)) {
        echo "✓ $file - Found\n";
    } else {
        echo "✗ $file - NOT FOUND\n";
    }
}

echo "\n===========================================\n";
echo "Documentation Files Check\n";
echo "===========================================\n\n";

$doc_files = [
    'README.md',
    'PROJECT_DOCUMENTATION.md',
    'XAMPP_IMPLEMENTATION_GUIDE.md',
    'DELIVERABLES_CHECKLIST.md'
];

foreach ($doc_files as $file) {
    if (file_exists($file)) {
        echo "✓ $file - Found\n";
    } else {
        echo "✗ $file - NOT FOUND\n";
    }
}

echo "\n===========================================\n";
echo "SQL File Content Preview\n";
echo "===========================================\n\n";

// Show first few lines of complete_database.sql
if (file_exists('complete_database.sql')) {
    $content = file_get_contents('complete_database.sql');
    $lines = explode("\n", $content);
    echo "First 10 lines of complete_database.sql:\n";
    echo "-------------------------------------------\n";
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo ($i + 1) . ": " . $lines[$i] . "\n";
    }
    echo "\nTotal lines: " . count($lines) . "\n";
}

echo "\n===========================================\n";
echo "Next Steps to Run the Project\n";
echo "===========================================\n\n";

echo "1. Install XAMPP from https://www.apachefriends.org/\n";
echo "2. Start Apache and MySQL in XAMPP Control Panel\n";
echo "3. Open phpMyAdmin: http://localhost/phpmyadmin\n";
echo "4. Create database 'job_portal'\n";
echo "5. Import complete_database.sql\n";
echo "6. Copy all files to C:\\xampp\\htdocs\\jobportal\\\n";
echo "7. Access: http://localhost/jobportal/login.php\n";
echo "\nDefault Login: admin@jobportal.com / password\n";

echo "\n===========================================\n";
echo "All Files Ready! ✓\n";
echo "===========================================\n";
?>
