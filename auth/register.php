<?php
session_start();
// This links the external database file
require_once '../includes/db.php';

$error = '';
$success = '';

$username_input = '';
$password_input = '';
$confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'student'; 

    if ($password_input !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

        // PDO method to check if username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username_input]);
        
        if ($stmt->rowCount() > 0) {
            $error = "This username is already taken.";
        } else {
            // PDO method to insert new user
            $insert_stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            
            if ($insert_stmt->execute([$username_input, $hashed_password, $role])) {
                $success = "Registration successful! You can now go to the login page.";
                $username_input = '';
                $password_input = '';
                $confirm_password = '';
            } else {
                $error = "Error saving to database.";
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
    <title>Register - Student Event System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    
                    <?php if ($error != ''): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success != ''): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username_input); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password_input); ?>" required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo htmlspecialchars($confirm_password); ?>" required minlength="6">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <a href="login.php">Already have an account? Login here.</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>























