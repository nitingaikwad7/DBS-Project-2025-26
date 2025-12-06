<?php
/**
 * Employer Registration Page
 * Online Job Portal
 */
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $company_name = sanitize_input($_POST['company_name']);
    $company_description = sanitize_input($_POST['company_description']);
    $industry = sanitize_input($_POST['industry']);
    $company_size = sanitize_input($_POST['company_size']);
    $website = sanitize_input($_POST['website']);
    $contact_person = sanitize_input($_POST['contact_person']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);
    $state = sanitize_input($_POST['state']);
    
    // Validation
    if (empty($email) || empty($password) || empty($company_name) || empty($contact_person) || empty($phone)) {
        $error = "All required fields must be filled";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email exists
        $check_query = "SELECT user_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_begin_transaction($conn);
            
            try {
                // Insert user
                $user_query = "INSERT INTO users (email, password, user_type, status) VALUES (?, ?, 'employer', 'active')";
                $stmt = mysqli_prepare($conn, $user_query);
                mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error creating user account");
                }
                
                $user_id = mysqli_insert_id($conn);
                
                // Insert employer
                $employer_query = "INSERT INTO employers (user_id, company_name, company_description, industry, company_size, website, contact_person, phone, address, city, state) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $employer_query);
                mysqli_stmt_bind_param($stmt, "issssssssss", $user_id, $company_name, $company_description, $industry, $company_size, $website, $contact_person, $phone, $address, $city, $state);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error creating employer profile");
                }
                
                mysqli_commit($conn);
                $success = "Registration successful! You can now login.";
                header("refresh:2;url=login.php");
                
            } catch (Exception $e) {
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
    <title>Employer Registration - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="url"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
        }
        textarea { resize: vertical; min-height: 80px; }
        .required { color: red; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #218838; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employer Registration</h2>
        
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
                <label>Company Name <span class="required">*</span></label>
                <input type="text" name="company_name" required>
            </div>
            
            <div class="form-group">
                <label>Company Description</label>
                <textarea name="company_description"></textarea>
            </div>
            
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry" placeholder="e.g., Information Technology">
            </div>
            
            <div class="form-group">
                <label>Company Size</label>
                <select name="company_size">
                    <option value="">Select Size</option>
                    <option value="1-10">1-10 employees</option>
                    <option value="10-50">10-50 employees</option>
                    <option value="50-100">50-100 employees</option>
                    <option value="100-500">100-500 employees</option>
                    <option value="500-1000">500-1000 employees</option>
                    <option value="1000+">1000+ employees</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" placeholder="https://example.com">
            </div>
            
            <div class="form-group">
                <label>Contact Person <span class="required">*</span></label>
                <input type="text" name="contact_person" required>
            </div>
            
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" required pattern="[0-9]{10,15}">
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
            
            <button type="submit">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
