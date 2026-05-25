<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check if fields are empty
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } 
    // 2. Check if passwords match
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } 
    else {
        // 3. Check if the username or email already exists in the database
        $check_stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_stmt->execute([$username, $email]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Username or email is already taken.";
        } else {
            // 4. Encrypt the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student'; // Default role for new users

            // 5. Insert new user into the database
            $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            
            if ($insert_stmt->execute([$username, $email, $hashed_password, $role])) {
                $success = "Registration successful! You can now <a href='login.php'>login here</a>.";
            } else {
                $error = "Failed to register. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white text-center">
            <h4 class="mb-0">Create an Account</h4>
        </div>
        <div class="card-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>

            <div class="mt-3 text-center">
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>