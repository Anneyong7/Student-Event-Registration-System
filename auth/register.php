<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check_stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_stmt->execute([$username, $email]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Username or email is already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';

            $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            
            if ($insert_stmt->execute([$username, $email, $hashed_password, $role])) {
                $success = "Registration successful! You can now <a href='login.php' class='alert-link'>login here</a>.";
            } else {
                $error = "Failed to register. Please try again.";
            }
        }
    }
}

$pageTitle = "Register";
include '../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm w-100" style="max-width: 480px;">
        <div class="card-header bg-success text-white text-center py-3">
            <h4 class="mb-0">Create an Account</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="register.php" novalidate>
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
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">Register</button>
            </form>

            <div class="mt-3 text-center">
                <p class="mb-0 text-muted">Already have an account? <a href="login.php" class="fw-bold">Login here</a>.</p>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>