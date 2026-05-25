<?php
session_start();
require_once '../includes/db.php';

// Ensure the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];

    // Check if the student is already registered
    $check_stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->execute([$user_id, $event_id]);

    if ($check_stmt->rowCount() > 0) {
        // Save warning message in session
        $_SESSION['message'] = "<div class='alert alert-warning'>You are already registered for this event.</div>";
    } else {
        // Insert new registration
        $insert_stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        if ($insert_stmt->execute([$user_id, $event_id])) {
            // Save success message in session
            $_SESSION['message'] = "<div class='alert alert-success'>Successfully registered!</div>";
        } else {
            // Save error message in session
            $_SESSION['message'] = "<div class='alert alert-danger'>Error during registration.</div>";
        }
    }
}

// Redirect back to the dashboard immediately
header("Location: dashboard.php");
exit();
?>