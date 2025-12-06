<?php
/**
 * Post Job Page (Employer)
 * Online Job Portal
 */
require_once 'config.php';
check_user_type('employer');

// Get employer_id
$user_id = $_SESSION['user_id'];
$query = "SELECT employer_id FROM employers WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$employer = mysqli_fetch_assoc($result);
$employer_id = $employer['employer_id'];

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = sanitize_input($_POST['job_title']);
    $job_description = sanitize_input($_POST['job_description']);
    $job_category = sanitize_input($_POST['job_category']);
    $job_type = sanitize_input($_POST['job_type']);
    $experience_required = intval($_POST['experience_required']);
    $salary_min = floatval($_POST['salary_min']);
    $salary_max = floatval($_POST['salary_max']);
    $location = sanitize_input($_POST['location']);
    $skills_required = sanitize_input($_POST['skills_required']);
    $vacancies = intval($_POST['vacancies']);
    $deadline = sanitize_input($_POST['deadline']);
    $status = sanitize_input($_POST['status']);
    
    // Validation
    if (empty($job_title) || empty($job_type) || empty($location)) {
        $error = "Required fields must be filled";
    } elseif ($salary_max < $salary_min) {
        $error = "Maximum salary must be greater than minimum salary";
    } elseif ($vacancies < 1) {
        $error = "Vacancies must be at least 1";
    } else {
        $query = "INSERT INTO jobs (employer_id, job_title, job_description, job_category, job_type, experience_required, salary_min, salary_max, location, skills_required, vacancies, deadline, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "issssiidssiss", $employer_id, $job_title, $job_description, $job_category, $job_type, $experience_required, $salary_min, $salary_max, $location, $skills_required, $vacancies, $deadline, $status);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Job posted successfully!";
        } else {
            $error = "Error posting job: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 20px; }
        .navbar a { color: white; text-decoration: none; margin-right: 20px; }
        .container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="date"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
        }
        textarea { resize: vertical; min-height: 100px; }
        .row { display: flex; gap: 15px; }
        .col { flex: 1; }
        .required { color: red; }
        button { padding: 12px 30px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #218838; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="employer_dashboard.php">Dashboard</a>
        <a href="post_job.php">Post Job</a>
        <a href="manage_jobs.php">Manage Jobs</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <h2>Post a New Job</h2>
        
        <?php 
        if ($error) echo display_error($error);
        if ($success) echo display_success($success);
        ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Job Title <span class="required">*</span></label>
                <input type="text" name="job_title" required>
            </div>
            
            <div class="form-group">
                <label>Job Description</label>
                <textarea name="job_description"></textarea>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Job Category</label>
                        <input type="text" name="job_category" placeholder="e.g., Software Development">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Job Type <span class="required">*</span></label>
                        <select name="job_type" required>
                            <option value="">Select Type</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Experience Required (Years)</label>
                        <input type="number" name="experience_required" min="0" value="0">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Vacancies</label>
                        <input type="number" name="vacancies" min="1" value="1">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Minimum Salary (₹)</label>
                        <input type="number" name="salary_min" min="0" step="1000">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Maximum Salary (₹)</label>
                        <input type="number" name="salary_max" min="0" step="1000">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Location <span class="required">*</span></label>
                <input type="text" name="location" required placeholder="e.g., Bangalore">
            </div>
            
            <div class="form-group">
                <label>Skills Required</label>
                <textarea name="skills_required" placeholder="e.g., Java, Spring Boot, MySQL"></textarea>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Application Deadline</label>
                        <input type="date" name="deadline">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit">Post Job</button>
        </form>
    </div>
</body>
</html>
