<?php
/**
 * Admin Dashboard
 * Online Job Portal
 */
require_once 'config.php';
check_user_type('admin');

// Get statistics
$stats = [];

// Total users
$query = "SELECT user_type, COUNT(*) as count FROM users GROUP BY user_type";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $stats[$row['user_type']] = $row['count'];
}

// Total jobs
$query = "SELECT status, COUNT(*) as count FROM jobs GROUP BY status";
$result = mysqli_query($conn, $query);
$job_stats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $job_stats[$row['status']] = $row['count'];
}

// Total applications
$query = "SELECT status, COUNT(*) as count FROM applications GROUP BY status";
$result = mysqli_query($conn, $query);
$app_stats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $app_stats[$row['status']] = $row['count'];
}

// Recent jobs
$query = "SELECT j.job_id, j.job_title, e.company_name, j.posted_date, j.status 
          FROM jobs j 
          INNER JOIN employers e ON j.employer_id = e.employer_id 
          ORDER BY j.posted_date DESC LIMIT 10";
$recent_jobs = mysqli_query($conn, $query);

// Recent applications
$query = "SELECT a.application_id, js.full_name, j.job_title, e.company_name, a.applied_date, a.status 
          FROM applications a 
          INNER JOIN job_seekers js ON a.seeker_id = js.seeker_id 
          INNER JOIN jobs j ON a.job_id = j.job_id 
          INNER JOIN employers e ON j.employer_id = e.employer_id 
          ORDER BY a.applied_date DESC LIMIT 10";
$recent_applications = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 20px; }
        .navbar a { color: white; text-decoration: none; margin-right: 20px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        h2 { color: #333; margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: bold; color: #007bff; }
        .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        .badge { padding: 4px 8px; border-radius: 3px; font-size: 12px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_all_jobs.php">Manage Jobs</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <h2>Admin Dashboard</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Job Seekers</h3>
                <div class="number"><?php echo isset($stats['job_seeker']) ? $stats['job_seeker'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Employers</h3>
                <div class="number"><?php echo isset($stats['employer']) ? $stats['employer'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Jobs</h3>
                <div class="number"><?php echo isset($job_stats['active']) ? $job_stats['active'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Applications</h3>
                <div class="number"><?php 
                    $total_apps = array_sum($app_stats);
                    echo $total_apps;
                ?></div>
            </div>
        </div>
        
        <div class="section">
            <h3>Recent Job Postings</h3>
            <table>
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Posted Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($job = mysqli_fetch_assoc($recent_jobs)): ?>
                    <tr>
                        <td><?php echo $job['job_id']; ?></td>
                        <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($job['posted_date'])); ?></td>
                        <td>
                            <?php 
                            $badge_class = $job['status'] == 'active' ? 'badge-success' : 'badge-warning';
                            echo '<span class="badge ' . $badge_class . '">' . $job['status'] . '</span>';
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <h3>Recent Applications</h3>
            <table>
                <thead>
                    <tr>
                        <th>App ID</th>
                        <th>Applicant</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($app = mysqli_fetch_assoc($recent_applications)): ?>
                    <tr>
                        <td><?php echo $app['application_id']; ?></td>
                        <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($app['applied_date'])); ?></td>
                        <td>
                            <?php 
                            $badge_map = [
                                'pending' => 'badge-warning',
                                'shortlisted' => 'badge-info',
                                'accepted' => 'badge-success',
                                'rejected' => 'badge-danger'
                            ];
                            $badge_class = $badge_map[$app['status']];
                            echo '<span class="badge ' . $badge_class . '">' . $app['status'] . '</span>';
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
