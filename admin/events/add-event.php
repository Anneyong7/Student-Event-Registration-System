<?php
session_start();
require_once '../../auth/db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $event_date = $_POST['date']; // Form uses 'date', save to 'event_date'
    $description = $_POST['description'];
    $slots = intval($_POST['slots']);

    // PDO method to insert data safely
    $stmt = $pdo->prepare("INSERT INTO events (title, event_date, description, slots) VALUES (?, ?, ?, ?)");

    if ($stmt->execute([$title, $event_date, $description, $slots])) {
        header("Location: events.php?msg=Event created successfully!");
        exit();
    } else {
        $error = "Error creating event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create New Event</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="add-event.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Event Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g., Guest Lecture">
                </div>
                <div class="mb-3">
                    <label class="form-label">Event Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Provide event details..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="slots" class="form-control" min="1" placeholder="e.g., 50" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="events.php" class="btn btn-secondary">Go Back</a>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
