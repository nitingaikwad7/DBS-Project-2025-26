<?php
/**
 * Login Page
 * Online Job Portal
 */
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        $query = "SELECT user_id, email, password, user_type FROM users WHERE email = ? AND status = 'active'";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_type'] = $row['user_type'];
                
                // Redirect based on user type
                if ($row['user_type'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($row['user_type'] == 'employer') {
                    header("Location: employer_dashboard.php");
                } else {
                    header("Location: jobseeker_dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
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
    <title>Login - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { max-width: 400px; width: 100%; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        h2 { color: #333; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; margin-top: 10px; }
        button:hover { background: #5568d3; }
        .alert-danger { padding: 12px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; }
        .register-links { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
        .register-links p { margin-bottom: 10px; color: #666; }
        .register-links a { display: inline-block; margin: 5px 10px; color: #667eea; text-decoration: none; font-weight: bold; }
        .register-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login to Job Portal</h2>
        
        <?php if ($error) echo '<div class="alert-danger">' . htmlspecialchars($error) . '</div>'; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="register-links">
            <p>Don't have an account?</p>
            <a href="register_jobseeker.php">Register as Job Seeker</a> | 
            <a href="register_employer.php">Register as Employer</a>
        </div>
    </div>
</body>
</html>
