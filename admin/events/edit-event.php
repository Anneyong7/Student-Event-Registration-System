<?php
require_once ' ../../auth/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $event = $result->fetch_assoc();
    } else {
        header("Location: events.php?msg=Event not found");
        exit();
    }
} else {
    header("Location: events.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $slots = intval($_POST['slots']);

    $update_stmt = $conn->prepare("UPDATE events SET title = ?, date = ?, description = ?, slots = ? WHERE id = ?");
    $update_stmt->bind_param("sssii", $title, $date, $description, $slots, $id);

    if ($update_stmt->execute()) {
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
                    <input type="date" name="date" class="form-control" value="<?php echo $event['date']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="slots" class="form-control" min="0" value="<?php echo $event['slots']; ?>" required>
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
