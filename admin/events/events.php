<?php
require_once ' ../../auth/db.php';

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        header("Location: events.php?msg=Event deleted successfully");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$query = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Event Management Dashboard</h2>
        <a href="add-event.php" class="btn btn-primary">+ Add New Event</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Slots</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . (strlen($row['description']) > 60 ? '...' : ''); ?></td>
                                <td><span class="badge bg-secondary"><?php echo $row['slots']; ?></span></td>
                                <td>
                                    <a href="edit-event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                    <a href="events.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete('<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No events found. Click "+ Add New Event" to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(title) {
    return confirm("Are you sure you want to permanently delete the event: '" + title + "'?");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
