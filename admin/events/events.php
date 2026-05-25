<?php
session_start();
// Step up two levels to find auth/db.php
require_once '../../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    if ($stmt->execute([$delete_id])) {
        header("Location: events.php?msg=Event deleted successfully");
        exit();
    }
}

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();

$pageTitle = "Manage Events";
$rootPath = '../../'; // Telling the header that assets are two levels up
include '../../includes/header.php';
?>

<div class="container">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h2>Event Management Dashboard</h2>
            <p>Create, update, or remove active student platform events</p>
        </div>
        <a href="add-event.php" class="btn btn-primary">+ Add New Event</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show alert-autohide">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card mt-4">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Slots</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($events) > 0): ?>
                    <?php foreach ($events as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><?php echo date('M d, Y', strtotime($row['event_date'])); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['description'], 0, 60)) . (strlen($row['description']) > 60 ? '...' : ''); ?></td>
                            <td><span class="badge bg-secondary"><?php echo $row['slots']; ?></span></td>
                            <td class="text-center">
                                <a href="edit-event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                <a href="events.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" data-confirm="Are you sure you want to permanently delete '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>'?">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No events found. Click "+ Add New Event" to get started.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
