<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

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

$pageTitle = "Login";
include '../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-sm w-100" style="max-width: 400px;">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0">System Login</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="login.php" novalidate>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
            </form>

            <div class="mt-3 text-center">
                <p class="mb-0 text-muted">Don't have an account? <a href="register.php" class="fw-bold">Register here</a>.</p>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>