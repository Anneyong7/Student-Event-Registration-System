<?php
session_start();
require_once '../auth/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Search the database for the username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the user exists and the password matches
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/events/events.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">System Login</h4>
        </div>
        <div class="card-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="mt-3 text-center">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>