<?php
/**
 * Job Seeker Registration Page
 * Online Job Portal
 */
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize_input($_POST['full_name']);
    $phone = sanitize_input($_POST['phone']);
    $date_of_birth = sanitize_input($_POST['date_of_birth']);
    $gender = sanitize_input($_POST['gender']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);
    $state = sanitize_input($_POST['state']);
    $qualification = sanitize_input($_POST['qualification']);
    $experience_years = intval($_POST['experience_years']);
    $skills = sanitize_input($_POST['skills']);
    
    // Validation
    if (empty($email) || empty($password) || empty($full_name) || empty($phone)) {
        $error = "All required fields must be filled";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Invalid phone number format (10-15 digits)";
    } else {
        // Check if email already exists
        $check_query = "SELECT user_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already registered";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Begin transaction
            mysqli_begin_transaction($conn);
            
            try {
                // Insert into users table
                $user_query = "INSERT INTO users (email, password, user_type, status) VALUES (?, ?, 'job_seeker', 'active')";
                $stmt = mysqli_prepare($conn, $user_query);
                mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error creating user account");
                }
                
                $user_id = mysqli_insert_id($conn);
                
                // Insert into job_seekers table
                $seeker_query = "INSERT INTO job_seekers (user_id, full_name, phone, date_of_birth, gender, address, city, state, qualification, experience_years, skills) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $seeker_query);
                mysqli_stmt_bind_param($stmt, "isssssssis", $user_id, $full_name, $phone, $date_of_birth, $gender, $address, $city, $state, $qualification, $experience_years, $skills);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error creating job seeker profile");
                }
                
                // Commit transaction
                mysqli_commit($conn);
                $success = "Registration successful! You can now login.";
                
                // Redirect to login after 2 seconds
                header("refresh:2;url=login.php");
                
            } catch (Exception $e) {
                // Rollback transaction on error
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
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
    <title>Job Seeker Registration - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="number"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
        }
        textarea { resize: vertical; min-height: 80px; }
        .required { color: red; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { color: #007bff; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Job Seeker Registration</h2>
        
        <?php 
        if ($error) echo display_error($error);
        if ($success) echo display_success($success);
        ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label>Confirm Password <span class="required">*</span></label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" required pattern="[0-9]{10,15}" placeholder="10-15 digits">
            </div>
            
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth">
            </div>
            
            <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <textarea name="address"></textarea>
            </div>
            
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city">
            </div>
            
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state">
            </div>
            
            <div class="form-group">
                <label>Qualification</label>
                <input type="text" name="qualification" placeholder="e.g., B.Tech Computer Science">
            </div>
            
            <div class="form-group">
                <label>Years of Experience</label>
                <input type="number" name="experience_years" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label>Skills</label>
                <textarea name="skills" placeholder="e.g., Java, Python, SQL, React"></textarea>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
