<?php
session_start();
require_once '../../auth/db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Fetch the event data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // PDO method
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() === 1) {
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: events.php?msg=Event not found");
        exit();
    }
} else {
    header("Location: events.php");
    exit();
}

// Handle the update submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $event_date = $_POST['date']; // The HTML form uses name="date"
    $description = $_POST['description'];
    $slots = intval($_POST['slots']);

    // PDO method with correct column name: event_date
    $update_stmt = $pdo->prepare("UPDATE events SET title = ?, event_date = ?, description = ?, slots = ? WHERE id = ?");
    
    if ($update_stmt->execute([$title, $event_date, $description, $slots, $id])) {
        header("Location: events.php?msg=Event updated successfully!");
        exit();
    } else {
        $error = "Failed to save updates.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Event</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="edit-event.php?id=<?php echo $id; ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Event Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Event Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="slots" class="form-control" min="0" value="<?php echo htmlspecialchars($event['slots']); ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="events.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning">Apply Updates</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
