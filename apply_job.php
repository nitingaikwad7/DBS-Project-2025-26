<?php
/**
 * Apply for Job Page (Job Seeker)
 * Online Job Portal
 */
require_once 'config.php';
check_user_type('job_seeker');

// Get seeker_id
$user_id = $_SESSION['user_id'];
$query = "SELECT seeker_id FROM job_seekers WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$seeker = mysqli_fetch_assoc($result);
$seeker_id = $seeker['seeker_id'];

$error = '';
$success = '';
$job = null;

// Get job_id from URL
if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    
    // Fetch job details
    $query = "SELECT j.*, e.company_name FROM jobs j 
              INNER JOIN employers e ON j.employer_id = e.employer_id 
              WHERE j.job_id = ? AND j.status = 'active'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $job_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $job = mysqli_fetch_assoc($result);
    
    if (!$job) {
        $error = "Job not found or no longer available";
    } else {
        // Check if already applied
        $check_query = "SELECT application_id FROM applications WHERE job_id = ? AND seeker_id = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ii", $job_id, $seeker_id);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "You have already applied to this job";
        }
    }
} else {
    $error = "No job selected";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $job && !$error) {
    $cover_letter = sanitize_input($_POST['cover_letter']);
    
    // Use stored procedure to apply
    $query = "CALL sp_apply_for_job(?, ?, ?, @result)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $job_id, $seeker_id, $cover_letter);
    
    if (mysqli_stmt_execute($stmt)) {
        // Get result
        $result_query = "SELECT @result as result";
        $result = mysqli_query($conn, $result_query);
        $row = mysqli_fetch_assoc($result);
        
        if (strpos($row['result'], 'SUCCESS') !== false) {
            $success = "Application submitted successfully!";
            header("refresh:2;url=jobseeker_dashboard.php");
        } else {
            $error = $row['result'];
        }
    } else {
        $error = "Error submitting application";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 20px; }
        .navbar a { color: white; text-decoration: none; margin-right: 20px; }
        .container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        .job-details { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .job-details h3 { color: #007bff; margin-bottom: 10px; }
        .job-details p { margin: 5px 0; color: #555; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; min-height: 150px; resize: vertical; }
        button { padding: 12px 30px; background: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="jobseeker_dashboard.php">Dashboard</a>
        <a href="browse_jobs.php">Browse Jobs</a>
        <a href="my_applications.php">My Applications</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <a href="browse_jobs.php" class="back-link">← Back to Jobs</a>
        
        <h2>Apply for Job</h2>
        
        <?php 
        if ($error) echo display_error($error);
        if ($success) echo display_success($success);
        ?>
        
        <?php if ($job && !$error): ?>
        <div class="job-details">
            <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Job Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
            <p><strong>Experience Required:</strong> <?php echo $job['experience_required']; ?> years</p>
            <p><strong>Salary:</strong> ₹<?php echo number_format($job['salary_min']); ?> - ₹<?php echo number_format($job['salary_max']); ?></p>
            <?php if ($job['skills_required']): ?>
            <p><strong>Skills Required:</strong> <?php echo htmlspecialchars($job['skills_required']); ?></p>
            <?php endif; ?>
        </div>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?job_id=' . $job_id; ?>">
            <div class="form-group">
                <label>Cover Letter</label>
                <textarea name="cover_letter" placeholder="Explain why you are a good fit for this position..." required></textarea>
            </div>
            
            <button type="submit">Submit Application</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
