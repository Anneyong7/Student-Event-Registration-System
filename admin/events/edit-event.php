<?php
session_start();
require_once '../../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    
    if (!$event) {
        header("Location: events.php?msg=Event not found");
        exit();
    }
} else {
    header("Location: events.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $event_date = $_POST['date'];
    $description = trim($_POST['description']);
    $slots = intval($_POST['slots']);

    if (empty($title) || empty($event_date) || empty($description) || $slots < 0) {
        $error = "Please supply valid updates.";
    } else {
        $update_stmt = $pdo->prepare("UPDATE events SET title = ?, event_date = ?, description = ?, slots = ? WHERE id = ?");
        if ($update_stmt->execute([$title, $event_date, $description, $slots, $id])) {
            header("Location: events.php?msg=Event updated successfully!");
            exit();
        } else {
            $error = "Failed to save updates.";
        }
    }
}

$pageTitle = "Edit Event";
$rootPath = '../../';
include '../../includes/header.php';
?>

<div class="container d-flex justify-content-center">
    <div class="card shadow-sm w-100" style="max-width: 600px;">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0">Edit Event</h4>
        </div>
        <div class="card-body p-4">
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form id="eventForm" action="edit-event.php?id=<?php echo $id; ?>" method="POST" novalidate>
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
                <div class="mb-4">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="slots" class="form-control" min="0" value="<?php echo htmlspecialchars($event['slots']); ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="events.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning text-dark">Apply Updates</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
